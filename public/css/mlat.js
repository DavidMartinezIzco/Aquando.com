function abrirCerrar() {

    
    if(document.getElementById("menuIzq").style.width == '15%'){
        cerrarMenu();
    }
    else{
        abrirMenu();
    }
    
}

function desplegar(menu) {
    if (menu == 'pruebas') {
        if(document.getElementsByClassName('expPruebas')[0].style.height == '0px'){
            document.getElementsByClassName('expPruebas')[0].style.height = "3.5em";
            document.getElementsByClassName('expPruebas')[1].style.height = "3.5em";
            document.getElementsByClassName('expPruebas')[2].style.height = "3.5em";
            document.getElementsByClassName('expPruebas')[3].style.height = "3.5em";
        }
        else{
            document.getElementsByClassName('expPruebas')[0].style.height = "0px";
            document.getElementsByClassName('expPruebas')[1].style.height = "0px";
            document.getElementsByClassName('expPruebas')[2].style.height = "0px";
            document.getElementsByClassName('expPruebas')[3].style.height = "0px";
        }
    }
    
    if(menu =='estaciones'){
        if (document.getElementsByClassName('miniEstacion')[0].style.height == '0px') {
            document.getElementsByClassName('miniEstacion')[0].style.height = '35%'; 
        }
        else{
            document.getElementsByClassName('miniEstacion')[0].style.height = '0px'; 
        }
    }

}

function abrirMenu() {
    if(document.getElementById("menuIzq")){
        document.getElementById("menuIzq").style.width = '15%';
        document.getElementById("contenido").style.paddingLeft = '15%';
        document.getElementById("btnMenuIzq").style.left = '15%';
        document.getElementById("btnMenuIzq").style.visibility = 'hidden';
    }
    if(document.getElementById("conInfo")){
        document.getElementById("conInfo").style.left = '16%';
        document.getElementById("conCarrusel").style.right = '2%';
    }

    if(document.getElementById("logoGrande")){
        document.getElementById("logoGrande").style.left = '39%';
        document.getElementById("logoGrande").style.top = '15%';
    }
    if(document.getElementById("alarmasSur")){
        document.getElementById("alarmasSur").style.marginLeft = '0%';
        document.getElementById("alarmasSur").style.width = '85%';
    }
    
}

function cerrarMenu() {
    if(document.getElementById("menuIzq")){
        document.getElementById("menuIzq").style.width = "0%";
        document.getElementById("contenido").style.paddingLeft = '3em';
        document.getElementById("btnMenuIzq").style.visibility = 'visible';
        document.getElementById("btnMenuIzq").style.left = '0%';
    }

    if(document.getElementById("conInfo")){
        document.getElementById("conInfo").style.left = '8%';
        document.getElementById("conCarrusel").style.right = '10%';
    }
    
    if(document.getElementById("logoGrande")){
        document.getElementById("logoGrande").style.left = '32%';
        document.getElementById("logoGrande").style.top = '30%';
    }
    
    if(document.getElementById("alarmasSur")){
        document.getElementById("alarmasSur").style.marginLeft = '-5%';
        document.getElementById("alarmasSur").style.width = '105%';
    }
}

function carga(){
    document.getElementById("seccion").innerText = "Cargando..."
}