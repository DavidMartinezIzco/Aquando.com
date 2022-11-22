//refresca la lista de alarmas del menu sur en funcion de la sección en la que se encuentre
function actualizarSur(entorno, nombre, pwd, estacion) {
  if (screen.width > 800) {
    if (entorno == "general") {
      $(document).ready(function () {
        $.ajax({
          type: "POST",
          url: "/Aquando.com/A_Sur.php",
          data: { caso: "general", nombre: nombre },
          success: function (alarmas) {
            document.getElementById("alarmasSur").innerHTML = alarmas;
          },
          error: function () {
            console.log("error");
          },
        });
      });
    }
    if (entorno == "estacion") {
      $(document).ready(function () {
        $.ajax({
          type: "POST",
          url: "/Aquando.com/A_Sur.php",
          data: { caso: "estacion", estacion: estacion },
          success: function (alarmas) {
            document.getElementById("alarmasSur").innerHTML = alarmas;
          },
          error: function () {
            console.log("error");
          },
        });
      });
    }
  }
}
function menuSur(){
    var menu = document.getElementById('alarmasSur');
    var btn = document.getElementById('btnAlSur');
    if(btn.style.bottom == '0px'){
      
      menu.style.bottom = '0px';
      btn.style.bottom = '180px';
    }else{
      
      menu.style.bottom = '-220px';
      btn.style.bottom = '0px';
    }
}