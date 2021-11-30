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