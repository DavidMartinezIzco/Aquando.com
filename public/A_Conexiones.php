<?php

require '../app/Database/Database.php';

$opcion = $_GET['opcion'];
$db = new Database();

//calcula el tiempo que lleva una estación sin comunicar
//determina en función del y tiempo basandose en unas reglas
//si la estación tiene problemas no
if ($opcion == 'conex') {

    $nombre = $_GET['nombre'];
    $pwd = $_GET['pwd'];


    $estaciones = $db->mostrarEstacionesCliente($nombre, $pwd);
    $ultimasConexiones = array();
    foreach ($estaciones as $estacion) {
        $ultimasConexiones[$estacion['nombre_estacion']] = $db->ultimaComunicacionEstacion($estacion['id_estacion']);
    }
    foreach ($ultimasConexiones as $estacion => $datos) {
        foreach ($datos[0] as $dato => $valor) {
            if ($dato == 'valor_date') {
                $ultima = new DateTime;
                $ultima = DateTime::createFromFormat('Y-m-d H:i:s', $valor);
                $ahora = new DateTime("now");
                $dif = $ahora->diff($ultima);
                $ultimasConexiones[$estacion][0]['estado'] = "correcto";
                if ($dif->days >= 1) {
                    $ultimasConexiones[$estacion][0]['estado'] = "aviso";
                }
                if ($dif->days >= 2) {
                    $ultimasConexiones[$estacion][0]['estado'] = "error";
                }
            }
        }
    }

    foreach ($ultimasConexiones as $estacion => $datos) {
        echo "<tr id='seccionEstacion' name=" . $datos[0]['id_estacion'] . ">";
        foreach ($datos[0] as $dato => $valor) {
            if ($dato == 'nombre_estacion') {
                echo "<td id='secNombre'>";
                echo $valor;
                echo "</td>";
            }
            if ($dato == 'valor_date') {
                echo "<td id='secUltima'>";
                echo "Última conexión: " . $valor;
                echo "</td>";
            }
            if ($dato == 'nombre_tag') {
            }
            if ($dato == 'id_estacion') {
            }
            if ($dato == 'estado') {
                if ($valor == "correcto") {
                    echo "<td id='secEstado'><i class='fas fa-check'></i></td>";
                } 
                if($valor == "error"){
                    echo "<td id='secProblema' class='' style='color:tomato'><i class='fas fa-exclamation-triangle'></i></td>";
                }
                if($valor == "aviso"){
                    echo "<td id='secProblema'><i name='alerta' class='fas fa-exclamation-triangle'></i></td>";
                }
            }
        }
        echo "</tr>";
    }
}

//busca la calidad de los ultimos datos(tags) de una estacion
// if ($opcion == 'cali') {
//     $id_estacion = $_GET['estacion'];

//     $calidadTags = $db->calidadTagsEstacion($id_estacion);

//     foreach ($calidadTags as $index => $tag) {
//         echo "<tr>";
//         foreach ($tag as $clave => $valor) {
//             echo "<td>";
//             if ($clave == 'nombre_tag') {
//                 echo $valor;
//             }
//             if ($clave == 'calidad') {
//                 echo "Calidad: " . sacarCalidad($valor);
//             }
//             echo "</td>";
//         }
//         echo "</tr>";
//     }
// }

//reglas de calidad OPC DA y mensajes
// function sacarCalidad($valor)
// {

//     $valor = intval($valor);
//     switch ($valor) {
//         case 0:
//             return "Mala";
//             break;
//         case 4:
//             return "Error de config";
//             break;
//         case 8:
//             return "Sin conectar";
//             break;
//         case 12:
//             return "Fallo de disp";
//             break;
//         case 16:
//             return "Error de Sensor";
//             break;
//         case 20:
//             return "Ultimo valor error";
//             break;
//         case 24:
//             return "Fallo de comunicación";
//             break;
//         case 28:
//             return "Fuera de servicio";
//             break;
//         case 64:
//             return "Incierto";
//             break;
//         case 65:
//             return "Incierto (lim alta)";
//             break;
//         case 66:
//             return "Incierto (lim baja)";
//             break;
//         case 67:
//             return "Incierto (constante)";
//             break;
//         case 68:
//             return "Incierto (ult usable)";
//             break;
//         case 69:
//             return "Incierto (ult usable baja)";
//             break;
//         case 70:
//             return "Incierto (ult usable alta)";
//             break;
//         case 71:
//             return "Incierto (ult usable alta)";
//             break;
//         case 80:
//             return "Sensor inpreciso";
//             break;
//         case 81:
//             return "Sensor inpreciso";
//             break;
//         case 82:
//             return "Sensor inpreciso";
//             break;
//         case 83:
//             return "Sensor inpreciso";
//             break;
//         case 84:
//             return "EU excedido";
//             break;
//         case 85:
//             return "EU excedido";
//             break;
//         case 86:
//             return "EU excedido";
//             break;
//         case 87:
//             return "EU excedido";
//             break;
//         case 88:
//             return "Incierto (sub-normal val)";
//             break;
//         case 89:
//             return "Incierto (sub-normal val)";
//             break;
//         case 90:
//             return "Incierto (sub-normal val)";
//             break;
//         case 91:
//             return "Incierto (sub-normal val)";
//             break;
//         case 180:
//             return "Buena (old)";
//             break;
//         case 192:
//             return "Buena";
//             break;
//         case 193:
//             return "Buena";
//             break;
//         case 194:
//             return "Buena";
//             break;
//         case 195:
//             return "Buena";
//             break;
//         case 216:
//             return "Buena";
//             break;
//         case 217:
//             return "Buena";
//             break;
//         case 218:
//             return "Buena";
//             break;
//         case 219:
//             return "Buena";
//             break;

//         default:
//             return "desconocida";
//             break;
//     }
// }

//obtiene los nombres de una estación dado su tag
if ($opcion == 'nom') {
    $id_estacion = $_GET['estacion'];

    $estacion = $db->obtenerNombreEstacion($id_estacion);
    echo ($estacion[0]['nombre_estacion']);
}
