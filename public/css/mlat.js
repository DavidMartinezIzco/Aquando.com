$(window).resize(function () {
  pantalla();
});
$(document).keypress(function (e) {
  if (e.ctrlKey && e.which == 0) {
    abrirCerrar();
  }
});
document.addEventListener("click", (evt) => {
  let targetEl = evt.target; // elem click
  do {
    if (targetEl == document.getElementById("menuIzq") || document.getElementById('btnMenuIzq')) {
      return;
    }
    targetEl = targetEl.parentNode;
  } while (targetEl);
  cerrarMenu();
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
function desplegar(menu) {
  if (menu == "estaciones") {
    if (
      document.getElementsByClassName("miniEstacion")[0].style.height == 0 ||
      document.getElementsByClassName("miniEstacion")[0].style.height == "0px"
    ) {
      document.getElementsByClassName("miniEstacion")[0].style.height = "15%";
    } else {
      document.getElementsByClassName("miniEstacion")[0].style.height = "0px";
    }
  }
  if (menu == "grafs") {
    if (
      document.getElementsByClassName("miniEstacion")[2].style.height == 0 ||
      document.getElementsByClassName("miniEstacion")[2].style.height == "0px"
    ) {
      document.getElementsByClassName("miniEstacion")[2].style.height = "116px";
    } else {
      document.getElementsByClassName("miniEstacion")[2].style.height = "0px";
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
    document.getElementById("iconoAyuda").style.display = "none";
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

    var defectoAncho = 550;
    var zoom = 100;
    var relAncho = (zoom * ancho) / defectoAncho;
    document.body.style.height = window.innerHeight;
    document.body.style.width = window.innerWidth;
    document.getElementById("menuIzq").style.height =
      2 * window.innerHeight + "px";

    for (var c of document.body.children) {
      if (c.tagName != "SCRIPT") {
        c.style.zoom = relAncho + "%";
      }
    }
    if (document.getElementById("seccion").innerText == "Informes") {
      document.body.style.zoom = relAncho + "%";
    }

    document.getElementById("contenido").style.zoom = "100%";
    //document.body.style.zoom = relAncho + "%";
    var defAltoVen = 949;

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
function temaAq(a) {
  var tema = 0;
  if (
    sessionStorage.getItem("tema") != undefined &&
    sessionStorage.getItem("tema") != null
  ) {
    tema = sessionStorage.getItem("tema");
  }
  if (a == "alt") {
    if (tema == 0) {
      tema = 1;
    } else {
      tema = 0;
    }
  }
  var estilos = {
    fondo: {
      0: "white",
      1: "dateando-fondo-oscuro.jpg",
    },
    fondoAlt: {
      0: "white",
      1: "rgb(85,85,85)",
    },
    fuente: {
      0: "black",
      1: "whitesmoke",
    },
    fuenteAlt: {
      0: "rgb(45,45,45)",
      1: "rgb(1, 168, 184)",
    },
  };
  sessionStorage.setItem("tema", tema);
  return estilos;
}
