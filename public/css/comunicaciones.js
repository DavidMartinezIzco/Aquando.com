function parpadeoProblema() {
    if(document.getElementById("secProblema").style.opacity == '0'){
        document.getElementById("secProblema").style.opacity = '100%'
    }
    else{
        document.getElementById("secProblema").style.opacity = '0%'
    }
}
function parpadeoError() {
    if(document.getElementById("secError").style.opacity == '0'){
        document.getElementById("secError").style.opacity = '100%'
    }
    else{
        document.getElementById("secError").style.opacity = '0%'
    }
}