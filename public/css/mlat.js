//abre o cierra el menu lateral
function abrirCerrar() {

    if (document.getElementById("menuIzq").style.width == '15%') {
        cerrarMenu();
    } else {
        abrirMenu();
    }

}

//despliega la zona de debug
function desplegar(menu) {
    if (menu == 'pruebas') {
        if (document.getElementsByClassName('expPruebas')[0].style.height == '0px') {
            document.getElementsByClassName('expPruebas')[0].style.height = "3.5em";
            document.getElementsByClassName('expPruebas')[1].style.height = "3.5em";
            document.getElementsByClassName('expPruebas')[2].style.height = "3.5em";
            document.getElementsByClassName('expPruebas')[3].style.height = "3.5em";
        } else {
            document.getElementsByClassName('expPruebas')[0].style.height = "0px";
            document.getElementsByClassName('expPruebas')[1].style.height = "0px";
            document.getElementsByClassName('expPruebas')[2].style.height = "0px";
            document.getElementsByClassName('expPruebas')[3].style.height = "0px";
        }
    }

    if (menu == 'estaciones') {
        if (document.getElementsByClassName('miniEstacion')[0].style.height == '0px') {
            document.getElementsByClassName('miniEstacion')[0].style.height = '20%';
        } else {
            document.getElementsByClassName('miniEstacion')[0].style.height = '0px';
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
    if (document.getElementById("amplificador").style.height == "0px") {
        document.getElementById("amplificador").style.height = '100%';
        document.getElementById("amplificador").style.padding = '1%';

    } else {
        document.getElementById("amplificador").style.height = '0px';
        document.getElementById("amplificador").style.padding = '0%';
    }
}

//anima textos mientras cargan cosas
function carga() {
    document.getElementById("seccion").innerText = "Cargando..."
}