$(window).resize(function () {
  pantalla();
});
$(document).keypress(function (e) {
  if (e.ctrlKey && e.which == 0) {
    abrirCerrar();
  }
});
//abre o cierra el menu lateral
function abrirCerrar() {
  if (document.getElementById("menuIzq").style.width == "15%") {
    cerrarMenu();
  } else {
    abrirMenu();
  }
  $("#menuIzq").trigger("widthChange");
}
//despliega la zona de debug
function desplegar(menu) {
  if (menu == "estaciones") {
    if (
      document.getElementsByClassName("miniEstacion")[0].style.height == 0 ||
      document.getElementsByClassName("miniEstacion")[0].style.height == "0%"
    ) {
      document.getElementsByClassName("miniEstacion")[0].style.height = "20%";
    } else {
      document.getElementsByClassName("miniEstacion")[0].style.height = "0%";
    }
  }
  if (menu == "grafs") {
    if (
      document.getElementsByClassName("miniEstacion")[2].style.height == 0 ||
      document.getElementsByClassName("miniEstacion")[2].style.height == "0%"
    ) {
      document.getElementsByClassName("miniEstacion")[2].style.height = "15%";
    } else {
      document.getElementsByClassName("miniEstacion")[2].style.height = "0%";
    }
  }
}
//abre el menu y aplica las nnuevas dimensiones
function abrirMenu() {
  var ancho = window.innerWidth;
  var aperturaMenu = "15%";
  if (ancho < 600) {
    aperturaMenu = "75%";
    document.getElementById("menuIzq").style.width = aperturaMenu;
    document.getElementById("btnMenuIzq").style.visibility = "hidden";
  }
  if (document.getElementById("menuIzq")) {
    document.getElementById("menuIzq").style.width = aperturaMenu;
    document.getElementById("btnMenuIzq").style.left = "15%";
    document.getElementById("btnMenuIzq").style.visibility = "hidden";
    document.getElementById("menuIzq").value = "abierto";
  }
  
}
//cierra el menu y aplica las nuevas dimensiones
function cerrarMenu() {
  $("#menuIzq").trigger("widthChange");
  if (document.getElementById("menuIzq")) {
    document.getElementById("menuIzq").style.width = "0%";
    document.getElementById("menuIzq").style.paddingLeft = "0";
    document.getElementById("menuIzq").style.paddingRight = "0";
    document.getElementById("btnMenuIzq").style.visibility = "visible";
    document.getElementById("btnMenuIzq").style.left = "0%";
    document.getElementById("menuIzq").value = "cerrado";
  }
  
}
//despliega el menu de inactividad
function tiempoOpciones() {
  if (
    document.getElementById("amplificador").style.height == 0 ||
    document.getElementById("amplificador").style.height == "0%"
  ) {
    document.getElementById("amplificador").style.height = "100%";
    document.getElementById("amplificador").style.padding = "1%";
  } else {
    document.getElementById("amplificador").style.height = "0%";
    document.getElementById("amplificador").style.padding = "0%";
  }
}
//anima textos mientras cargan cosas
function carga() {
  document.getElementById("seccion").innerText = "Cargando...";
}
function pantalla() {
  var altoVen = window.innerHeight;
  document.getElementById("menuIzq").style.height = altoVen + "px";
  var ancho = window.innerWidth;
  if (ancho > 600 && ancho < 900) {
    document.getElementById("btnGrafRap").disabled = "false";
    document.getElementById("btnGrafPer").disabled = "true";
    document.getElementById('iconoAyuda').style.display = 'none';
    var defectoAncho = 1920;
    var relAncho = (window.innerWidth / defectoAncho) * 90;
    document.body.style.zoom = relAncho + "%";
    document.body.style.fontSize = "large";
    document.getElementById("menuIzq").style.height = screen.height * 3 + "px";
  }
  if (ancho > 600) {
    document.getElementById("btnGrafRap").disabled = false;
    document.getElementById("btnGrafPer").disabled = false;
    if (document.getElementById("conPrincipal") != undefined) {
      var defAltoCon = 848;
      var defAltoVen = 949;
      var altoVen = window.innerHeight;

      var altoCon = (defAltoCon * altoVen) / defAltoVen;
      if (document.getElementById("alarmasSur") != undefined) {
        var altoAlarm = 150 * (altoCon / defAltoCon);
        // document.getElementById( "conPrincipal" )
        //     .style.height = altoCon - altoAlarm + "px";
      } else {
        // document.getElementById( "conPrincipal" )
        //     .style.height = altoCon + "px";
      }
    }
  } else {
    document.getElementsByClassName("btnHerrGraf")[0].disabled = "true";
    document.getElementById("btnGrafRap").disabled = "true";
    document.getElementById("btnGrafPer").disabled = "true";
    var defectoAncho = 600;
    var zoom = 100;
    var relAncho = (zoom * ancho) / defectoAncho;
    document.body.style.zoom = relAncho + "%";
    var defAltoVen = 949;
    document.getElementById("menuIzq").style.height =
      2 * window.innerHeight + "px";
    if (document.getElementById("alarmasSur") != undefined) {
      var relAlto = window.innerHeight / defAltoVen;
      var alAl = 150 * relAlto + "px";
      document.getElementById("alarmasSur").style.height = alAl;
      // document.getElementById( 'conPrincipal' )
      // .style.marginBottom = alAl;
    } else {
      // document.getElementById( 'conPrincipal' )
      //     .style.height = window.innerHeight + 'px';
    }
  }
}
