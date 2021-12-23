<?= $this->extend('inicio') ?>
<?= $this->section('content') ?>
<script src='css/echarts.js'></script>
<script src='css/comunicaciones.js'></script>
<link rel="stylesheet" type="text/css" href="css/comunicaciones.css">
<main id="conPrincipal" style="height: 53em; width:100%; border-radius:10px; margin-top:1%;">


    <!--zona con los estados de conexiones-->
    <!--su contenido es provisional-->
    <div id="displayComs">
        <table id="tablaConex"style="width:100%; height:100%">

        <?php 
            // foreach ($conexiones as $estacion => $datos) {
            //     echo "<tr id='seccionEstacion'>";
            //         foreach ($datos[0] as $dato => $valor) {
            //             if($dato == 'nombre_estacion'){
            //                 echo "<td id='secNombre'>";
            //                 echo "ESTACION: " . $valor;
            //                 echo "</td>";
            //             }
            //             if($dato == 'valor_date'){
            //                 echo "<td id='secUltima'>";
            //                 echo "ULTIMA CONEXION: " . $valor;
            //                 echo "</td>";
            //             }
            //             if($dato == 'nombre_tag'){
                            
            //             }
            //             if($dato == 'estado'){
            //                 if($valor == "correcto"){
            //                     echo "<td id='secEstado'><i class='fas fa-check'></i></td>"; 
            //                 }
            //                 else {
            //                     echo "<i class='fas fa-exclamation-triangle'></i>";
            //                 }
            //             }
            //         }
            //     echo "</tr>";
            // }
        ?>
        </table>
<!-- 
            <tr onclick="graficoConex()" id='seccionEstacion'>
                <td id='secNombre'>
                    N Estacion
                </td>
                <td id='secEstado'>
                    <i class="fas fa-check"></i>
                </td>
                <td id='secUltima'>
                    Ultima Conexion
                </td>
            </tr>

            <tr onclick="graficoConex()" id='seccionEstacion'>
                <td id='secNombre'>
                    N Estacion
                </td>
                <td id='secEstado'>
                    <i class="fas fa-check"></i>
                </td>
                <td id='secUltima'>
                    Ultima Conexion
                </td>
            </tr>

            <tr onclick="graficoConex()" id="seccionEstacionProblema">
                <td id="secNombre">
                    N Estacion
                </td>

                <td id="secProblema">
                    <i class="fas fa-exclamation-triangle"></i>
                </td>

                <td id="secUltima">
                    Ultima Conexion
                </td>
            </tr>

            <tr onclick="graficoConex()" id="seccionEstacionError">
                <td id="secNombre">
                    N Estacion
                </td>

                <td id="secError">
                    <i class="fas fa-times-circle"></i>

                </td>

                <td id="secUltima">
                    Ultima Conexion
                </td>

            </tr>

            <tr onclick="graficoConex()" id='seccionEstacion'>
                <td id='secNombre'>
                    N Estacion
                </td>
                <td id='secEstado'>
                    <i class="fas fa-check"></i>
                </td>
                <td id='secUltima'>
                    Ultima Conexion
                </td>
            </tr>

            <tr onclick="graficoConex()" id="seccionEstacionProblema">
                <td id="secNombre">
                    N Estacion
                </td>

                <td id="secProblema">
                    <i class="fas fa-exclamation-triangle"></i>
                </td>

                <td id="secUltima">
                    Ultima Conexion
                </td>
            </tr> -->

        </table>
    </div>

    <!--sitio para el grafico de resumen del estado-->
    <div id="seccionDetalles1">
        <div id="graficoConexion" style="width: 720px; height:350px;"></div>
    </div>

    <!--sitio para mas informacion-->
    <div id="seccionDetalles2">
        detalles de la estacion seleccionada (?)
    </div>
<!--alarmas de abajo-->
<table id="alarmasSur">
</table>
</main>




<script>
    window.onload = function() {
        var usu = '<?php echo $_SESSION['nombre'] ?>';
        var pwd = '<?php echo $_SESSION['pwd'] ?>';
        var idusu = <?php echo $_SESSION['idusu']?>;
        actualizarSur('general',usu, pwd, idusu, null);
        graficoConex();
        setInterval(actualizarConexiones(usu, pwd, idusu), 1000*60*5);
        setInterval(fechaYHora, 1000);
        setInterval(comprobarTiempo, 1000);
        // setInterval(parpadeoProblema, 1000);
        // setInterval(parpadeoError, 1000);
        $(window).blur(function() {
            tiempoFuera("");
        });
        $(window).focus(function() {
            tiempoFuera("volver")
        });
    }
</script>


<?= $this->endSection() ?>