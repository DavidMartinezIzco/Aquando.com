function ayuda(){
    if(document.getElementById("conAyuda").style.opacity == '0'){
        document.getElementById("conAyuda").style.opacity = '100%';
        document.getElementById("conAyuda").style.visibility ='visible';
    }
    else{
        document.getElementById("conAyuda").style.opacity = '0%';
        document.getElementById("conAyuda").style.visibility ='hidden';
    }
}

function ayudaNext() {
    
    var tip ="";

    if (localStorage.getItem("tip") === null) {
        tip = 1;
      }
    else{
        tip = localStorage.getItem("tip");
    }
    tip ++;
    switch (tip) {
        case 1:
            document.getElementById("conAyuda").innerHTML = '<i class="fas fa-times" id="btnAyudaCerrar" onclick="ayuda()"></i><h3>Ayuda:</h3><p id="txtAyuda">pulsa "espacio" para abrir o cerrar el menu</p><button id="btnAyudaNext" name="ayudaNext" onclick="ayudaNext()"><i class="fas fa-arrow-right"></i></button>';
            break;
        case 2:
            document.getElementById("conAyuda").innerHTML = '<i class="fas fa-times" id="btnAyudaCerrar" onclick="ayuda()"></i><h3>Ayuda:</h3><p id="txtAyuda">puedes modificar la inactividad pinchando en el contador del menú</p><button id="btnAyudaNext" name="ayudaNext" onclick="ayudaNext()"><i class="fas fa-arrow-right"></i></button>';
            break;
        case 3:
            document.getElementById("conAyuda").innerHTML = '<i class="fas fa-times" id="btnAyudaCerrar" onclick="ayuda()"></i><h3>Ayuda:</h3><p id="txtAyuda">hacer click en una alarma en el resumen inferior te llevará a mas detalles de esta</p><button id="btnAyudaNext" name="ayudaNext" onclick="ayudaNext()"><i class="fas fa-arrow-right"></i></button>';
            break;
        
        default:
            tip = 1;
            document.getElementById("conAyuda").innerHTML = '<i class="fas fa-times" id="btnAyudaCerrar" onclick="ayuda()"></i><h3>Ayuda:</h3><p id="txtAyuda">pulsa "espacio" para abrir o cerrar el menu</p><button id="btnAyudaNext" name="ayudaNext" onclick="ayudaNext()"><i class="fas fa-arrow-right"></i></button>';
            break;
    }
    
    localStorage.setItem("tip", tip);

}

