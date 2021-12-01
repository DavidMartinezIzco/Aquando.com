function opciones() {
    if(document.getElementById("informesNorte").style.height == '15%'){
        document.getElementById("informesNorte").style.height = '0%';
        document.getElementById("btnMenuInformes").style.top = '6%';
    }
    else{
        document.getElementById("informesNorte").style.height = '15%';
        document.getElementById("btnMenuInformes").style.top = '19.5%';
    }
}