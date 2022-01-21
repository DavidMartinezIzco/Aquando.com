<?= $this->extend('inicio') ?>
<?= $this->section('content') ?>
<script src='css/echarts.js'></script>
<script src='css/estaciones.js'></script>
<link rel="stylesheet" type="text/css" href="css/estaciones.css">
    <main id="conPrincipal" style="height: 50em; width:100%;border-radius:10px; margin-top:1%">
        <!---zona superior--->

        <div id="estacionIzq">
            <div id="seccionSup">
                <div id="seccionFoto"></div>
                <div id="seccionInfo">
                <i class="fas fa-tools" onclick="ajustes()" style="float:right;color: rgb(1, 168, 184);"></i>
                    <p><?php echo $ultimaConex[0]['nombre_estacion'];  ?></p>
                    <p>Ultima comunicación: <?php echo $ultimaConex[0]['valor_date']  ?></p>
                </div>
            </div>
            <div id="seccionInf">
                <!-- HTML DE PROTO WID DIGI ON-->
                <!-- <div class="widDigi">
                    <div class="widDigiIcono"><i style="color:darkseagreen;" class="fas fa-toggle-on"></i></i></div>
                    <div class="widDigiText">widget digital prototipo ON</div>
                </div> -->
                <!-- HTML DE PROTO WID DIGI OFF-->
                <!-- <div class="widDigi">
                    <div class="widDigiIcono"><i style="color:tomato;" class="fas fa-toggle-off"></i></div>
                    <div class="widDigiText">widget digital prototipo OFF</div>
                </div> -->
            </div>
        </div>

        <div id="estacionDer">
        </div>

        <div id="ajustesEstacion">
        <i class="fas fa-times" id="btnAjustesCerrar" onclick="ajustes()"></i>
            <h1>Ajustes de estación <?php echo $nombreEstacion; ?></h1>
            
                <table id="tablaConsignas">

                </table>
            
        </div>

        <!---zona alarmas--->
        
        <table id="alarmasSur">
        </table>
        
        
    </main>


    <script>
        var estacion = <?php echo $id_estacion ?>;
        window.onload = function() {
            actualizarSur('estacion', null, null, null, estacion);
            comprobarTiempo();
            setInterval(fechaYHora, 1000);
            setInterval(actualizar(estacion), 60000);
            setInterval(actualizarSur('estacion', null, null, null, estacion), 20000);
            setInterval(comprobarTiempo, 1000);
            $(window).blur(function() {
                tiempoFuera("");
            });
            $(window).focus(function() {
                tiempoFuera("volver")
            });
        }
    </script>

    <?= $this->endSection() ?>