//hace aparecer y desaparecer el texto de desconexion
function desvanecer() {
    if (document.getElementById('txtDesconectado')) {
        if (document.getElementById('txtDesconectado').style.opacity == '0') {
            document.getElementById('txtDesconectado').style.opacity = "60%";
        } else {
            document.getElementById('txtDesconectado').style.opacity = "0%";
        }
    }
}