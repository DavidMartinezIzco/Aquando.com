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

        </table>
    </div>

    <!--sitio para el grafico de resumen del estado-->
    <div id="seccionDetalles1">
        <h4 id="calidadSenales">Calidad de señales:</h4>
        <table id="seccionCalidad">
        </table>
    </div>

    <!--sitio para mas informacion-->
    <div id="seccionDetalles2">
        <table>
        <tr>
            <td><button id="btnControl" value="actualizar" onclick="actu()" name=""><i id="iconoActu" class="fas fa-sync"></i></button></td>
            <td><button id="btnControl" value="ir" onclick="" style="font-size: 185%;" value="reset" name=""><i class="fas fa-broadcast-tower"></i></i></button></td>
            <td><button id="btnControl" value="ajustes" onclick="" name=""><i class="fas fa-cog"></i></button></td>
        </tr>
        </table>
        
    </div>
<!--alarmas de abajo-->
<table id="alarmasSur">
</table>
</main>




<script>
    pantalla();
    window.onload = function() {
        var usu = '<?php echo $_SESSION['nombre'] ?>';
        sessionStorage.setItem('usu', usu);
        var pwd = '<?php echo $_SESSION['pwd'] ?>';
        sessionStorage.setItem('pwd', pwd);
        var idusu = <?php echo $_SESSION['idusu']?>;
        sessionStorage.setItem('idusu', idusu);
        actualizarSur('general',usu, pwd, idusu, null);
        
        setInterval(actualizarConexiones(usu, pwd, idusu), 1000*60*5);
        setInterval(fechaYHora, 1000);
        setInterval(comprobarTiempo, 1000);
        setInterval(parpadeoProblema, 3000);
        $(window).blur(function() {
            tiempoFuera("");
        });
        $(window).focus(function() {
            tiempoFuera("volver")
        });
    }

    function actu(){
        usu = sessionStorage.getItem('usu');
        pwd = sessionStorage.getItem('pwd');
        idusu = sessionStorage.getItem('idusu');
        actualizarConexiones(usu,pwd,idusu);
    }
</script>



<?= $this->endSection() ?>