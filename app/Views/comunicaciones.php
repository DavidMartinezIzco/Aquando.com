<?= $this->extend('inicio') ?>
<?= $this->section('content') ?>
<script src='css/echarts.min.js'></script>
<script src='css/comunicaciones.js'></script>
<link rel="stylesheet" type="text/css" href="css/comunicaciones.css">
<main id="conPrincipal" style="height: 53em; width:100%; border-radius:10px; margin-top:1%; padding: 0.5%">



</main>

<script>

window.onload = function () {
    setInterval(fechaYHora, 1000);
    setInterval(comprobarTiempo, 1000);
}

</script>


<?= $this->endSection() ?>