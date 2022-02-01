$(window).resize(function() {
    pantalla();
});

//abre o cierra el menu lateral
function abrirCerrar() {
    if (document.getElementById("menuIzq").style.width == '15%') {
        cerrarMenu();
    } else {
        abrirMenu();
    }

    if (typeof grafico !== 'undefined') {
        grafico.resize();
    }
    if (typeof graficoCustom !== 'undefined') {
        graficoCustom.resize();
    }


}

//despliega la zona de debug
function desplegar(menu) {

    if (menu == 'estaciones') {
        if (document.getElementsByClassName('miniEstacion')[0].style.height == 0 || document.getElementsByClassName('miniEstacion')[0].style.height == '0%') {
            document.getElementsByClassName('miniEstacion')[0].style.height = '20%';
        } else {
            document.getElementsByClassName('miniEstacion')[0].style.height = '0%';
        }
    }

    if (menu == 'grafs') {
        if (document.getElementsByClassName('miniEstacion')[2].style.height == 0 || document.getElementsByClassName('miniEstacion')[2].style.height == '0%') {
            document.getElementsByClassName('miniEstacion')[2].style.height = '15%';
        } else {
            document.getElementsByClassName('miniEstacion')[2].style.height = '0%';
        }
    }

}

//abre el menu y aplica las nnuevas dimensiones
function abrirMenu() {
    if (document.getElementById("menuIzq")) {
        document.getElementById("menuIzq").style.width = '15%';
        document.getElementById("contenido").style.paddingLeft = '15%';
        document.getElementById("btnMenuIzq").style.left = '15%';
        document.getElementById("btnMenuIzq").style.visibility = 'hidden';
    }
    if (document.getElementById("conInfo")) {
        document.getElementById("conInfo").style.left = '16%';
        document.getElementById("conCarrusel").style.right = '2%';
    }

    if (document.getElementById("logoGrande")) {
        document.getElementById("logoGrande").style.left = '39%';
        document.getElementById("logoGrande").style.top = '15%';
    }
    if (document.getElementById("alarmasSur")) {
        document.getElementById("alarmasSur").style.marginLeft = '0%';
        document.getElementById("alarmasSur").style.width = '85%';
    }

    if (document.getElementById("displayComs")) {
        document.getElementById("displayComs").style.left = '20%';
        document.getElementById("displayComs").style.width = '50%';
        document.getElementById("seccionDetalles1").style.left = '72%';
        document.getElementById("seccionDetalles1").style.width = '25%';
        document.getElementById("seccionDetalles2").style.width = '25%';
        document.getElementById("seccionDetalles2").style.left = '72%';
    }
    if (document.getElementById("btnOpcionesgraficas")) {
        document.getElementById("btnOpcionesgraficas").style.left = '81%';
    }




}

//cierra el menu y aplica las nuevas dimensiones
function cerrarMenu() {

    if (document.getElementById("menuIzq")) {
        document.getElementById("menuIzq").style.width = "0%";
        document.getElementById("contenido").style.paddingLeft = '0%';
        document.getElementById("btnMenuIzq").style.visibility = 'visible';
        document.getElementById("btnMenuIzq").style.left = '0%';
    }

    if (document.getElementById("conInfo")) {
        document.getElementById("conInfo").style.left = '8%';
        document.getElementById("conCarrusel").style.right = '10%';
    }

    if (document.getElementById("logoGrande")) {
        document.getElementById("logoGrande").style.left = '32%';
        document.getElementById("logoGrande").style.top = '30%';
    }

    if (document.getElementById("alarmasSur")) {
        document.getElementById("alarmasSur").style.width = '100%';

    }

    if (document.getElementById("displayComs")) {
        document.getElementById("displayComs").style.left = '5%';
        document.getElementById("displayComs").style.width = '50%';
        document.getElementById("seccionDetalles1").style.left = '58%';
        document.getElementById("seccionDetalles1").style.width = '40%';
        document.getElementById("seccionDetalles2").style.width = '40%';
        document.getElementById("seccionDetalles2").style.left = '58%';
    }


}

//despliega el menu de inactividad
function tiempoOpciones() {
    if (document.getElementById("amplificador").style.height == 0 || document.getElementById("amplificador").style.height == '0%') {
        document.getElementById("amplificador").style.height = '100%';
        document.getElementById("amplificador").style.padding = '1%';

    } else {
        document.getElementById("amplificador").style.height = '0%';
        document.getElementById("amplificador").style.padding = '0%';
    }
}

//anima textos mientras cargan cosas
function carga() {
    document.getElementById("seccion").innerText = "Cargando..."
}

function pantalla() {

    var ancho = window.innerWidth;
    var alto = 0;
    var defectoAlto = 848;
    if (document.getElementById("conPrincipal")) {
        alto = parseInt(document.getElementById("conPrincipal").style.height);
        var nuevoAlto = (defectoAlto / 100) * alto;
        document.getElementById("conPrincipal").style.height = nuevoAlto;
    }

    var defectoAncho = 1880;
    var zoom = 100;
    var relAncho = ((zoom * ancho) / defectoAncho);
    document.body.style.zoom = relAncho + '%';

    if (document.getElementById("grafica")) {

        document.getElementById("zonaControles").style.zoom = relAncho + '%';
        document.getElementById("alarmasSur").style.zoom = relAncho + '%';
        console.log("aplico en graficas");


        // document.getElementById("zonaGraficos").style.zoom = '100%';
        // document.getElementById("grafica").style.zoom = '100%';
        // var defancho = 1210;
        // var defalto = 622;
        // let zonaG = document.getElementById("grafica").firstChild;
        // var gancho = parseInt(zonaG.style.width);
        // var galto = parseInt(zonaG.style.height);
        // var nancho = (gancho * 100) / defancho;
        // var nalto = (galto * 100) / defalto;


        // document.getElementById("grafica").style.width = nancho + '%';
        // document.getElementById("grafica").style.height = nalto + '%';
        // console.log(nancho);
        // console.log(nalto);
    } else {
        document.body.style.zoom = relAncho + '%';
    }

}