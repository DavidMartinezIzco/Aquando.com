//hace aparecer o desaparecer la caja de tips
function ayuda() {
    document.getElementById("conPrincipal").onclick = function() {
        if (!document.getElementById("conAyuda").style.opacity == '0') {
            document.getElementById("conAyuda").style.opacity = '0%';
            document.getElementById("conAyuda").style.visibility = 'hidden';
        }
    };
    if (document.getElementById("conAyuda").style.opacity == '0') {
        document.getElementById("conAyuda").style.opacity = '100%';
        document.getElementById("conAyuda").style.visibility = 'visible';
        ayudaNext();
    } else {
        document.getElementById("conAyuda").style.opacity = '0%';
        document.getElementById("conAyuda").style.visibility = 'hidden';
    }

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
            document.getElementById("conAyuda").innerHTML = '<i class="fas fa-times" id="btnAyudaCerrar" onclick="ayuda()"></i><h3>Ayuda:</h3><p id="txtAyuda">pulsa "espacio" para abrir o cerrar el menu</p><img src="barra.gif" alt="" width="50%" id="imgAyuda"/><br><button id="btnAyudaNext" name="ayudaNext" onclick="ayudaNext()"><i class="fas fa-arrow-right"></i></button>';
            break;
        case 2:
            document.getElementById("conAyuda").innerHTML = '<i class="fas fa-times" id="btnAyudaCerrar" onclick="ayuda()"></i><h3>Ayuda:</h3><p id="txtAyuda">Por seguridad, la sesión se cierra automáticamente si estás inactivo. Puedes ajustar la inactividad máxima pinchando en el contador del menú</p><img src="inac.gif" alt=""  width="50%" id="imgAyuda"/><br><button id="btnAyudaNext" name="ayudaNext" onclick="ayudaNext()"><i class="fas fa-arrow-right"></i></button>';
            break;
        case 3:
            document.getElementById("conAyuda").innerHTML = '<i class="fas fa-times" id="btnAyudaCerrar" onclick="ayuda()"></i><h3>Ayuda:</h3><p id="txtAyuda">Hacer "click" en una alarma en el resumen inferior te llevará a más detalles de esta</p><button id="btnAyudaNext" name="ayudaNext" onclick="ayudaNext()"><i class="fas fa-arrow-right"></i></button>';
            break;
        case 4:
            document.getElementById("conAyuda").innerHTML = '<i class="fas fa-times" id="btnAyudaCerrar" onclick="ayuda()"></i><h3>Ayuda:</h3><p id="txtAyuda">Pulsa "Ctrl" para ocultar/mostrar opciones en algunos gráficos</p><img src="opciones.gif" alt=""  width="50%" id="imgAyuda"/><br><button id="btnAyudaNext" name="ayudaNext" onclick="ayudaNext()"><i class="fas fa-arrow-right"></i></button>';
            break;
        case 5:
            document.getElementById("conAyuda").innerHTML = '<i class="fas fa-times" id="btnAyudaCerrar" onclick="ayuda()"></i><h3>Ayuda:</h3><p id="txtAyuda">Algunos gráficos con muchos datos tienen la posibilidad de hacer zoom y arrastrar con el ratón</p><img src="zoom.gif" alt=""  width="50%" id="imgAyuda"/><br><button id="btnAyudaNext" name="ayudaNext" onclick="ayudaNext()"><i class="fas fa-arrow-right"></i></button>';
            break;
        case 6:
            document.getElementById("conAyuda").innerHTML = '<i class="fas fa-times" id="btnAyudaCerrar" onclick="ayuda()"></i><h3>Ayuda:</h3><p id="txtAyuda">La sección de inicio representa la información mas relevante según niveles de alarmas de las estaciones</p><img src="inicio.gif" alt=""  width="50%" id="imgAyuda"/><br><button id="btnAyudaNext" name="ayudaNext" onclick="ayudaNext()"><i class="fas fa-arrow-right"></i></button>';
            break;
        case 7:
            document.getElementById("conAyuda").innerHTML = '<i class="fas fa-times" id="btnAyudaCerrar" onclick="ayuda()"></i><h3>Ayuda:</h3><p id="txtAyuda">Puedes reajustar la anchura de los gráficos depués de cerrar menús con tan sólo mover el ratón por encima ("click" no necesario)</p><img src="resize.gif" alt=""  width="50%" id="imgAyuda"/><br><button id="btnAyudaNext" name="ayudaNext" onclick="ayudaNext()"><i class="fas fa-arrow-right"></i></button>';
            break;
        default:
            tip = 1;
            document.getElementById("conAyuda").innerHTML = '<i class="fas fa-times" id="btnAyudaCerrar" onclick="ayuda()"></i><h3>Ayuda:</h3><p id="txtAyuda">Pulsa "SHIFT/MayusIzq" para abrir o cerrar el menú lateral de navegación</p><img src="barra.gif" alt="" width="50%" id="imgAyuda"/><br><button id="btnAyudaNext" name="ayudaNext" onclick="ayudaNext()"><i class="fas fa-arrow-right"></i></button>';
            break;
    }

    localStorage.setItem("tip", tip);

}