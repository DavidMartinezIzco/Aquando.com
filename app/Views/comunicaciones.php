<?= $this->extend('inicio') ?>
<?= $this->section('content') ?>
<script src='css/echarts.js'></script>
<script src='css/comunicaciones.js'></script>
<link rel="stylesheet" type="text/css" href="css/comunicaciones.css">
<link rel="stylesheet" type="text/css" href="css/sur.css">
<main id="conPrincipal" style="width:100%; border-radius:10px; margin-top:1%;">


    <!--zona con los estados de conexiones-->
    <!--su contenido es provisional-->
    <div id="displayComs">
        <table id="tablaConex" style="width:100%; height:100%">

        </table>
    </div>


    <!--alarmas de abajo-->
    <table id="alarmasSur">
    </table>
</main>




<script>
    window.onload = function() {
        var usu = '<?php echo $_SESSION['nombre'] ?>';
        sessionStorage.setItem('usu', usu);
        var pwd = '<?php echo $_SESSION['pwd'] ?>';
        sessionStorage.setItem('pwd', pwd);

        actualizarSur('general', usu, pwd, null);

        setInterval(actualizarConexiones(usu, pwd), 1000 * 60 * 5);
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

    function actu() {
        usu = sessionStorage.getItem('usu');
        pwd = sessionStorage.getItem('pwd');
        idusu = sessionStorage.getItem('idusu');
        actualizarConexiones(usu, pwd, idusu);
    }
</script>



<?= $this->endSection() ?>