function actualizarConexiones(nombre, pwd) {
  $(document).ready(function () {
    $.ajax({
      type: "POST",
      url: "/Aquando.com/A_Conexiones.php",
      data: {
        nombre: nombre,
        pwd: pwd,
        opcion: "conex",
      },
      success: function (conex) {
        document.getElementById("tablaConex").innerHTML = conex;
      },
      error: function () {
        console.log("error");
      },
    });
  });
}
function parpadeoProblema() {
  desvanecer();
  setTimeout(aparecer, 1000);
}
function desvanecer() {
  var nalertas = document.getElementsByName("alerta").length;
  for (var i = 0; i < nalertas; i++) {
    document.getElementsByName("alerta")[i].style.opacity = "0";
  }
}
function aparecer() {
  var nalertas = document.getElementsByName("alerta").length;
  for (var i = 0; i < nalertas; i++) {
    document.getElementsByName("alerta")[i].style.opacity = "1";
  }
}
function nombrarEstacion(estacion) {
  $(document).ready(function () {
    $.ajax({
      type: "POST",
      url: "/Aquando.com/A_Conexiones.php",
      data: {
        estacion: estacion,
        opcion: "nom",
      },
      success: function (est) {
        document.getElementById("calidadSenales").innerHTML =
          '<h4 id="calidadSenales"> Calidad de señal: ' + est + "</h4>";
      },
      error: function () {
        console.log("error");
      },
    });
  });
}
