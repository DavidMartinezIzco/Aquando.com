<?= $this->extend('inicio') ?>

<?= $this->section('content') ?>




<link rel="stylesheet" type="text/css" href="css/estilos.css">
<link rel="canonical" href="https://getbootstrap.com/docs/5.1/examples/sign-in/">
<link href="/docs/5.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-uWxY/CJNBR+1zjPWmfnSnVxwRheevXITnMqoEIeG1LJrdI0GlVs/9cVSyPYXdcSF" crossorigin="anonymous">


<main class="form-signin" style="padding: 4em; width:100%;">
<img id="logoGrande" src="../public/logo.png">

  <form method="POST" id="formulario" >  
    <h1 class="h3 mb-3 fw-normal" style="color: white;">Inicio de sesión</h1>
    <div class="form-floating" style="margin-bottom: 15px;">
      <select class="form-control" name="selEmpresa">
        <option value="1">Iturri Ederra</option>
        <option value="2">Amescoa Alta</option>
        <option value="3">Amescoa Baja</option>
        <option value="5">Dateando</option>
      </select>
      <label for="floatingInput"style="color:black">Empresa</label>
    </div>
    <div class="form-floating" style="margin-bottom: 15px;">
      <input type="text" name="txtNombre" class="form-control">
      <label for="floatingInput"style="color:black">Usuario</label>
    </div>
    <div class="form-floating" style="margin-bottom: 15px;">
      <input type="password" name="txtContrasena" class="form-control">
      <label for="floatingPassword"style="color:black">Contraseña</label>
      <p id="mayusAct"></p>
    </div>
    
    <button id="btnForm" class="w-100 btn btn-lg " style="background-color: rgb(56, 56, 56);margin-bottom: 100em; opacity:100%; color: white" type="submit">Iniciar Sesión</button>
  </form>
</main>

<script>
  //capta el evento de tener el mayus activado
  document.addEventListener( 'keydown', function( event ) {
  var mayus = event.getModifierState && event.getModifierState( 'CapsLock' );
  console.log( mayus ); 
  if(mayus){
    document.getElementById("mayusAct").innerHTML = 'Mayúsculas activadas';
  }
  else{
    document.getElementById("mayusAct").innerHTML = '';
  }
       
});
</script>

<?= $this->endSection() ?>