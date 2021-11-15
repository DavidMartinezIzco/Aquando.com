<?= $this->extend('inicio') ?>
<?= $this->section('content') ?>
<script src='css/echarts.min.js'></script>
<link rel="stylesheet" type="text/css" href="css/estilos.css">


<main style="padding:0em 3em; color:black">
<script>
    function mostrarCanales(estacion){
        sessionStorage.setItem('estacion', estacion);
        var acc = "<?php if(isset($_SESSION['acc'])){echo $_SESSION['acc'];}else{echo "";}?>"
        var pwd = "<?php if(isset($_SESSION['pwd'])){echo $_SESSION['pwd'];}else{echo "";}?>"
        var pass = "<?php if(isset($_SESSION['pass'])){echo $_SESSION['pass'];}else{echo "";}?>"
        $(document).ready(function(){
        
        $.ajax({
            type: 'GET',
            url: 'AjaxPTR.php?estacion=' + estacion + '&acc=' + acc + '&pwd= ' + pwd + '&pass=' + pass,
            success: function(datos) {
                $("#listaCanales").text("")
                $("#listaCanales").append(datos)
            }
        });
        
    });
    
}
function mostrarTAG(canal) {

var estacion = sessionStorage.getItem('estacion');
$(document).ready(function(){
    
    $.ajax({
        type: 'GET',
        url: 'AjaxPTR.php?canal=' +  canal +'&estacion=' + estacion,
        success: function(datos) {
            
            $("#listaTag").html(datos)
        }
    });
    
});
return false
}

</script>
<?php

use CodeIgniter\Config\ForeignCharacters;

echo "<h1>ESTACIONES:</h1><table class='customScroll' style='padding:0.5em;max-width:100%;overflow:auto;display:inline-block;'><tr>";
    foreach ($estaciones as $estacion=>$nombre) {
        if(!$estacion == 0){
            echo '<th >
            <button class="btn btn-outline-dark me-2 btn-block" name="btnEstacion" onclick= mostrarCanales(this.value) value="'. $nombre .'">
            <i class="far fa-chart-bar"></i> '.$nombre.'
            </button>
            </th>';
        }
    }
    echo "</tr></table>";

 
?>
    <div id="listaCanales">
    </div>
    <div id="listaTag">
    </div>

</main>
<?= $this->endSection() ?>