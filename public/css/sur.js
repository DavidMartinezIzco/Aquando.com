
//animación para las alarmas activas
function latido(alarma){
    setTimeout(function(){alarma.style.backgroundColor = "#ff726e"}, 500);
    alarma.style.backgroundColor = "#de3d37";
}

//actualiza los datos de alarmas en la seccion sur
function actualizarMini(){

    $(document).ready(function(){
                
        $.ajax({
            type: 'GET',
            url: 'A_Sur.php',
            success: function(alarmas) {
                $("#alarmasSur").html(alarmas);
            }
        });
        
    });
    
}

//da color a las alarmas con "pintar"
function colores(){
    var elems = document.getElementsByClassName("filaAl");
    for (var i = 0; i < elems.length; i++) {
        pintar(elems[i]);
      }
}

//da colores distintos en funcion del tipo de alarma
function pintar(elem){
    if(elem.innerHTML.indexOf("Motivo : 3") !== -1) {
        elem.style.backgroundColor = "rgb(144, 238, 144)";
    }

    if(elem.innerHTML.indexOf("Motivo : 1") !== -1) {
        elem.style.backgroundColor = "rgb(139, 3, 3)";
        latido(elem);
        setTimeout(latido(elem), 1000);
    }


}