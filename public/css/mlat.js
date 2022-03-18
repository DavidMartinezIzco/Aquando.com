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
    $("#menuIzq").trigger('widthChange');
    if (typeof grafico !== undefined) {
        grafico.resize();
    }
    if (typeof graficoCustom !== undefined) {
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

    var ancho = window.innerWidth;
    var aperturaMenu = "15%";
    if (ancho < 600) {
        aperturaMenu = "50%";
        document.getElementById("menuIzq").style.width = aperturaMenu;
    }
    if (document.getElementById("menuIzq")) {
        document.getElementById("menuIzq").style.width = aperturaMenu;
        document.getElementById("contenido").style.paddingLeft = '15%';
        document.getElementById("btnMenuIzq").style.left = '15%';
        document.getElementById("btnMenuIzq").style.visibility = 'hidden';
        document.getElementById("menuIzq").value = 'abierto';
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
    // if (document.getElementById("displayComs")) {
    //     document.getElementById("displayComs").style.left = '20%';
    //     document.getElementById("displayComs").style.width = '50%';

    // }
    if (document.getElementById("btnOpcionesgraficas")) {
        document.getElementById("btnOpcionesgraficas").style.left = '81%';
    }

}

//cierra el menu y aplica las nuevas dimensiones
function cerrarMenu() {
    $("#menuIzq").trigger('widthChange');
    if (document.getElementById("menuIzq")) {
        document.getElementById("menuIzq").style.width = "0%";
        document.getElementById("contenido").style.paddingLeft = '0%';
        document.getElementById("btnMenuIzq").style.visibility = 'visible';
        document.getElementById("btnMenuIzq").style.left = '0%';
        document.getElementById("menuIzq").value = 'cerrado';
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
    // if (document.getElementById("displayComs")) {
    //     document.getElementById("displayComs").style.left = '5%';
    //     document.getElementById("displayComs").style.width = '50%';
    // }

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
    if (ancho > 600) {
        var defectoAncho = 1879;
        var zoom = 100;
        var relAncho = ((zoom * ancho) / defectoAncho);
        document.body.style.zoom = relAncho + '%';
        if (document.getElementById("conPrincipal") != undefined) {
            var defAltoCon = 848;
            var defAltoVen = 949;
            var altoVen = window.innerHeight;

            var altoCon = (defAltoCon * altoVen) / defAltoVen;
            if (document.getElementById("alarmasSur") != undefined) {
                var altoAlarm = (150 * (altoCon / defAltoCon));
                document.getElementById("conPrincipal").style.height = altoCon - altoAlarm + "px";
            } else {
                document.getElementById("conPrincipal").style.height = altoCon + "px";
            }
        }
    } else {
        document.getElementsByClassName("btnHerrGraf")[0].disabled = true;
        var defectoAncho = 516;
        var zoom = 100;
        var relAncho = ((zoom * ancho) / defectoAncho);
        document.body.style.zoom = relAncho + '%';
        var defAltoVen = 949;
        if (document.getElementById("alarmasSur") != undefined) {
            var relAlto = window.innerHeight / defAltoVen;
            var alAl = (150 * relAlto) + 'px';
            document.getElementById("alarmasSur").style.height = alAl;
            document.getElementById('conPrincipal').style.marginBottom = 10 + alAl + 'px';
        } else {
            document.getElementById('conPrincipal').style.height = window.innerHeight - 60 + 'px';
        }
    }
}