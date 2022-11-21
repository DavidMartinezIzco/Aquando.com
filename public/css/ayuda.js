//hace aparecer o desaparecer la caja de tips
function ayuda() {
  if (document.getElementById("conAyuda").style.opacity == 0) {
    document.getElementById("conAyuda").style.opacity = "100%";
    document.getElementById("conAyuda").style.visibility = "visible";
    ayudaNext();
  } else {
    document.getElementById("conAyuda").style.opacity = "0%";
    document.getElementById("conAyuda").style.visibility = "hidden";
  }
  document.getElementById("conPrincipal").onclick = function () {
    if (!document.getElementById("conAyuda").style.opacity == "0") {
      document.getElementById("conAyuda").style.opacity = "0%";
      document.getElementById("conAyuda").style.visibility = "hidden";
    }
  };
}
//cambia al siguiente mensaje de ayuda de la caja de tips
function ayudaNext() {
  var tip = "";
  if (localStorage.getItem("tip") === null) {
    tip = 1;
  } else {
    tip = localStorage.getItem("tip");
  }
  tip++;
  switch (tip) {
    case 1:
      document.getElementById("conAyuda").innerHTML =
        '<i class="fas fa-times" id="btnAyudaCerrar" onclick="ayuda()"></i><h3>Ayuda:</h3><p id="txtAyuda">pulsa "Ctrl + espacio" para abrir o cerrar el menu</p><br><button id="btnAyudaNext" name="ayudaNext" onclick="ayudaNext()"><i class="fas fa-arrow-right"></i></button>';
      break;
    case 2:
      document.getElementById("conAyuda").innerHTML =
        '<i class="fas fa-times" id="btnAyudaCerrar" onclick="ayuda()"></i><h3>Ayuda:</h3><p id="txtAyuda">Por seguridad, la sesión se cierra automáticamente si estás inactivo. Puedes ajustar la inactividad máxima pinchando en el contador del menú</p><br><button id="btnAyudaNext" name="ayudaNext" onclick="ayudaNext()"><i class="fas fa-arrow-right"></i></button>';
      break;
    case 3:
      document.getElementById("conAyuda").innerHTML =
        '<i class="fas fa-times" id="btnAyudaCerrar" onclick="ayuda()"></i><h3>Ayuda:</h3><p id="txtAyuda">Puedes personalizar las señales mostradas en la pantalla de inicio desde el botón de ajustes</p><button id="btnAyudaNext" name="ayudaNext" onclick="ayudaNext()"><i class="fas fa-arrow-right"></i></button>';
      break;
    case 4:
      document.getElementById("conAyuda").innerHTML =
        '<i class="fas fa-times" id="btnAyudaCerrar" onclick="ayuda()"></i><h3>Ayuda:</h3><p id="txtAyuda">Pulsa "Ctrl + Z" para ocultar/mostrar opciones en algunos gráficos</p><br><button id="btnAyudaNext" name="ayudaNext" onclick="ayudaNext()"><i class="fas fa-arrow-right"></i></button>';
      break;
    case 5:
      document.getElementById("conAyuda").innerHTML =
        '<i class="fas fa-times" id="btnAyudaCerrar" onclick="ayuda()"></i><h3>Ayuda:</h3><p id="txtAyuda">Algunos gráficos con muchos datos tienen la posibilidad de hacer zoom y arrastrar con el ratón</p><br><button id="btnAyudaNext" name="ayudaNext" onclick="ayudaNext()"><i class="fas fa-arrow-right"></i></button>';
      break;
    case 6:
      document.getElementById("conAyuda").innerHTML =
        '<i class="fas fa-times" id="btnAyudaCerrar" onclick="ayuda()"></i><h3>Ayuda:</h3><p id="txtAyuda">Puedes guardar la configuración de las gráficas en la vista personalizada</p><br><button id="btnAyudaNext" name="ayudaNext" onclick="ayudaNext()"><i class="fas fa-arrow-right"></i></button>';
      break;
    case 7:
      document.getElementById("conAyuda").innerHTML =
        '<i class="fas fa-times" id="btnAyudaCerrar" onclick="ayuda()"></i><h3>Ayuda:</h3><p id="txtAyuda">Puedes reajustar la anchura de los gráficos depués de cerrar menús con tan sólo mover el ratón por encima ("click" no necesario)</p><br><button id="btnAyudaNext" name="ayudaNext" onclick="ayudaNext()"><i class="fas fa-arrow-right"></i></button>';
      break;
    case 8:
      document.getElementById("conAyuda").innerHTML =
        '<i class="fas fa-times" id="btnAyudaCerrar" onclick="ayuda()"></i><h3>Ayuda:</h3><p id="txtAyuda">Algunas estaciones pueden modificar sus consignas (se actualizarán al comunicar la estación).</p><br><button id="btnAyudaNext" name="ayudaNext" onclick="ayudaNext()"><i class="fas fa-arrow-right"></i></button>';
      break;
    case 9:
      document.getElementById("conAyuda").innerHTML =
        '<i class="fas fa-times" id="btnAyudaCerrar" onclick="ayuda()"></i><h3>Ayuda:</h3><p id="txtAyuda">Descarga la App de Aquando para poder ver tus estaciones desde tu dispositivo (Android v5+).</p><br><button id="btnAyudaNext" name="ayudaNext" onclick="ayudaNext()"><i class="fas fa-arrow-right"></i></button>';
      break;
    case 10:
      document.getElementById("conAyuda").innerHTML =
        '<i class="fas fa-times" id="btnAyudaCerrar" onclick="ayuda()"></i><h3>Ayuda:</h3><p id="txtAyuda">Dependiendo de tu resolución es posible que tengas que ajustar el zoom de la página para que se muestre correctamente.</p><br><button id="btnAyudaNext" name="ayudaNext" onclick="ayudaNext()"><i class="fas fa-arrow-right"></i></button>';
      break;
    default:
      tip = 1;
      document.getElementById("conAyuda").innerHTML =
        '<i class="fas fa-times" id="btnAyudaCerrar" onclick="ayuda()"></i><h3>Ayuda:</h3><p id="txtAyuda">Pulsa "Ctrl + espacio" para abrir o cerrar el menú lateral de navegación</p><br><button id="btnAyudaNext" name="ayudaNext" onclick="ayudaNext()"><i class="fas fa-arrow-right"></i></button>';
      break;
  }
  localStorage.setItem("tip", tip);
}
