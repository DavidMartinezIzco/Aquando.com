<?= $this->extend('inicio') ?>
<?= $this->section('content') ?>
<script src='css/echarts.min.js'></script>
<link rel="stylesheet" type="text/css" href="css/estaciones.css">
<main id="conPrincipal" style="height: 50em; overflow-y:scroll; width:100%;border-radius:10px; margin-top:1%">
<!---zona superior--->
<div id="estacionSup">
        <div id="elemGraf">
        
        </div>

        <div id="sinopMedio">
            
            <div id="sinopMI">
                <h3>información</h3>
                <hr>
                <ul>
                    <li>Estación:</li>
                    <li>Ultima conexión:</li>
                </ul>
            </div>
            <div id="sinopMD">
                
                <ul>
                    <li>
                        <div class="widDigital">
                            indicador:
                        </div>
                    </li>
                    <li>
                        <div class="widDigital">
                            indicador:
                        </div>
                    </li>
                    <li>
                        <div class="widDigital">
                            indicador:
                        </div>
                    </li>
                    <li>
                        <div class="widDigital">
                            indicador:
                        </div>
                    </li>
                    
                </ul>
            </div>
        </div>

        <div id="sinopLado">
            <ul>
                <li class="widMed">
                        <div class="widMedDatos">
                            <ul>
                                <li>dato 1</li>
                                <li>dato 2</li>
                                <li>dato 3</li>
                            </ul>
                        </div>
                        <div class="widMedGraf">
                            y aquí el gráfico
                        </div>
                </li>
                <li class="widMed">
                        <div class="widMedDatos">
                            <ul>
                                <li>dato 1</li>
                                <li>dato 2</li>
                                <li>dato 3</li>
                            </ul>
                        </div>
                        <div class="widMedGraf">
                            y aquí el gráfico
                        </div>
                </li>
            </ul>
        </div>
</div>


<!---zona media--->
<div id="estacionMedio">
    <div id="estMedI">
        <h3>Zona Media Izquierda</h3>
    </div>

    <div id="estMedC">
    <h3>Zona Media Centro</h3>
    </div>

    <div id="estMedD">
    <h3>Zona Media Derecha</h3>
    </div>
</div>

</main>
<!---zona alarmas--->
<table id="alarmasSur">

</table>



<script>
        window.onload = function() {
        actualizarMini();
        
        setInterval(fechaYHora, 1000);
        setInterval(actualizarMini, 3000);
        
        setInterval(comprobarTiempo, 1000);
    }
</script>

<?= $this->endSection() ?>