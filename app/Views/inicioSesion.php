<?= $this->extend('inicio') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" type="text/css" href="../../../css/estilos.css">
<link rel="canonical" href="https://getbootstrap.com/docs/5.1/examples/sign-in/">
<link href="/docs/5.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-uWxY/CJNBR+1zjPWmfnSnVxwRheevXITnMqoEIeG1LJrdI0GlVs/9cVSyPYXdcSF" crossorigin="anonymous">
<main class="form-signin" style="padding: 4em; width:100%;">
    <img id="logoGrande" src="../../logo.png">
    <form method="POST" id="formulario">
        <p id="mayusAct" style="text-align:center"><br></p>
        <div class="form-floating" style="margin-bottom: 15px;">
            <input type="text" name="txtNombre" class="form-control">
            <label for="floatingInput" style="color:black">Usuario</label>
        </div>
        <div class="form-floating" style="margin-bottom: 15px;">
            <input type="password" name="txtContrasena" class="form-control">
            <label for="floatingPassword" style="color:black">Contraseña</label>
        </div>
        <button id="btnForm" type="submit">Iniciar Sesión</button>
    </form>
</main>
<script>
window.onload = function() {
    //capta el evento de tener el mayus activado
    document.addEventListener('keydown', function(event) {
        var mayus = event.getModifierState && event.getModifierState('CapsLock');
        if (mayus) {
            document.getElementById("mayusAct").innerHTML = 'Mayúsculas activadas';
        } else {
            document.getElementById("mayusAct").innerHTML = '<br>';
        }

    });
}
</script>
<?= $this->endSection() ?>