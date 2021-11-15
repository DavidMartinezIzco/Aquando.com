<?= $this->extend('inicio') ?>

<?= $this->section('content') ?>

<main style="padding-left: 3em; color:black">
    <h1>Aqui voy a probar la conexion</h1>
    <?php 
      
    echo "<br><hr><h3>Estaciones en línea:</h3>";
    foreach ($estaciones as $estacion=>$nombre) {
        if(!$estacion == 0)
        echo $nombre ."  ";
    }
    echo "<br><hr><h3>Info de la BD:</h3>";
    if(!empty($serverInfo)){
        foreach ($serverInfo as $dato => $info) {
            echo $dato . ": " . $info . "<br>";
        }
    }
    echo "<hr>";
    ?>
</main>

<?= $this->endSection() ?>