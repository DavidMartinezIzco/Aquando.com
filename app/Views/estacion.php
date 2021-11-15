<?= $this->extend('inicio') ?>
<?= $this->section('content') ?>
<script src='css/echarts.min.js'></script>
<link rel="stylesheet" type="text/css" href="css/estaciones.css">
<main id="conPrincipal" style="margin-left:2.5%; height: 53em; overflow-y:scroll; background-color: rgb(56, 56, 56); width:100%; padding:1%;border-radius:10px; margin-top:1%">
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
                <h3>luces</h3>
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


<!---zona alarmas--->
<div id="estacionBajo">
    <table>
        <tr>
            <th></th>
            <th>Fecha y Hora</th>
            <th> Origen</th>
            <th>Estado</th>
            <th>Etiqueta</th>
        </tr>
        <tr>
            <td><input type="checkbox" disabled></td>
            <td>patata</td>
            <td>patata</td>
            <td>patata</td>
            <td>patata</td>
        </tr>
        <tr>
        <td><input type="checkbox" disabled></td>
            <td>patata</td>
            <td>patata</td>
            <td>patata</td>
            <td>patata</td>
        </tr>
        <tr>
        <td><input type="checkbox" disabled></td>
            <td>patata</td>
            <td>patata</td>
            <td>patata</td>
            <td>patata</td>
        </tr>
        
        
        
    </table>
</div>


</main>
<?= $this->endSection() ?>