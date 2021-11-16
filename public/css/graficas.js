function limpiar(){
    document.getElementsByName('btnControlReset')[0].innerText = 'limpio!';
    document.getElementById();
    setTimeout(function(){
        document.getElementsByName('btnControlReset')[0].innerHTML = "reset";
    }, 1000);
}



option && grafico.setOption(option);

document.getElementById('contenido').onclick = function(){
    grafico.resize();
    cerrarMenu();
}

document.getElementById('conPrincipal').onmouseover = function(){
    grafico.resize();
}

function imprimir() {
    html2canvas(document.querySelector("#grafica")).then(canvas => {
    document.body.appendChild(canvas)
    });
}