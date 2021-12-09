
//reestablece los filtros por defecto
function limpiar(){
    document.getElementsByName('btnControlReset')[0].innerText = 'limpio!';
    var i = 1;
    while(i <= 9){
        if (document.getElementsByName(i)[0].checked) {
            document.getElementsByName(i)[0].checked = false;
        }
        i++
    }

    document.getElementsByName(1)[0].checked = true;
    document.getElementsByName(2)[0].checked = true;
    document.getElementsByName(3)[0].checked = true;

    document.getElementById("tipoBarra").checked = false;
    document.getElementById("tipoLinea").checked = true;
    document.getElementById("opciones").value = 'p1';



    setTimeout(function(){
        document.getElementsByName('btnControlReset')[0].innerHTML = "reset";
    }, 1000);
}



//aplica las opciones de los controles
function aplicarOpciones() {
    var datosR = [];
    var datos = {
        "info 1":[320, 332, 301, 334, 390, 330, 320],
        "info 2":[120, 132, 101, 134, 90, 230, 210],
        "info 3":[220, 182, 191, 234, 290, 330, 310],
        "info 4":[150, 232, 201, 154, 190, 330, 410],
        "info 5":[862, 1018, 964, 1026, 1679, 1600, 1570],
        "info 6":[620, 732, 701, 734, 1090, 1130, 1120],
        "info 7":[120, 132, 101, 134, 290, 230, 220],
        "info 8":[60, 72, 71, 74, 190, 130, 110],
        "info 9":[62, 82, 91, 84, 109, 110, 120]};

    var i = 1;
    while(i <= 9){
        if (document.getElementsByName(i)[0].checked) {
            datosR.push(datos["info "+i]);
        }
        i++
    }
    var tipo = "barra";

    if(document.getElementById("tipoLinea").checked){
        tipo = "linea";
    }
    if(document.getElementById("tipoTarta").checked){
        tipo = "tarta";
    }
    renderGrafico(tipo, datosR);

    
}

//prepara el grafico
function renderGrafico(tipo,datosR) {

    var chartDom = document.getElementById('grafica');
    var grafico = echarts.init(chartDom);
    var option;
    var formato = "";

    //Ajustes
    option = {
        tooltip: {
            trigger: 'axis',
            axisPointer: {
            type: 'shadow'
            }
        },
        legend: {},
        grid: {
            left: '3%',
            right: '4%',
            bottom: '3%',
            containLabel: true
        },
        
        };


    if(tipo == "barra"){
        formato = "bar";

        option['xAxis'] = [
            {
            type: 'category',
            data: ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo']
            }
        ]
        option['yAxis'] = [
            {
            type: 'value'
            }
        ]
        
        var series = []

        datosR.forEach(function(valores, index, array){
                
            var datorender = {
                name: "dato "+ index,
                type: formato,
                smooth: true,
                emphasis: {
                    focus: 'series'
                },
                data: valores
                }
    
            
            series.push(datorender);
            
        });
        option['series'] = series;

    }

    if(tipo == "linea"){
        formato = "line";
        option['xAxis'] = [
            {
            type: 'category',
            data: ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo']
            }
        ]
        option['yAxis'] = [
            {
            type: 'value'
            }
        ]

        var series = []

        datosR.forEach(function(valores, index, array){
                
            var datorender = {
                name: "dato "+ index,
                type: formato,
                smooth: true,
                emphasis: {
                    focus: 'series'
                },
                data: valores
                }
    
            
            series.push(datorender);
            
        });
        option['series'] = series;
    }

    if(tipo == "tarta"){

        formato = "pie";
        
        var datos = [];
        datosR.forEach(function(valores, index, array){    
                datos.push({value: valores[6], name: 'dato '+index});
        });

        var series = [{
            name: "Prototipo Chart",
                type: formato,
                radius: '70%',
                data: datos
            } 
        ]
        option['series'] = series;
        option['tooltip'] = {trigger:'item'};
    }

    
    

    option && grafico.setOption(option, true);
    
    document.getElementById("infoGraf").innerHTML = "formato: "+ tipo + "<br>Periodo: Semanal";

    $(window).keyup(function(){
        setTimeout(grafico.resize(),500);
    });
    
    document.getElementById('conPrincipal').onmouseover = function(){
        setTimeout(grafico.resize(),500);
    }
    document.getElementById('grafica').onmouseover = function(){
        setTimeout(grafico.resize(),500);
    }
    document.getElementById('zonaControles').onmouseover = function(){
        setTimeout(grafico.resize(),500);
    }
    

}

//muestra o esconde las opciones de los graficos
function mostrarOpciones() {
    if (document.getElementById("zonaControles").style.width == '1%') {
        document.getElementById("zonaControles").style.width = '19.5%';
        document.getElementById("zonaControles").style.left = '80%';
        document.getElementById("zonaGraficos").style.width = '80%';
        
    }
    else{
        document.getElementById("zonaControles").style.width = '1%';
        document.getElementById("zonaControles").style.left = '100%';
        document.getElementById("zonaGraficos").style.width = '98%';
        
    }
    
}

//saca una captura del grafico en panatalla
function imprimir() {
    html2canvas(document.querySelector('#grafica')).then(function(canvas) {
        guardar(canvas.toDataURL(), 'grafico.png');
    });
    
}

//descarga la captura del grafico
function guardar(uri, filename) {

    var link = document.createElement('a');

    if (typeof link.download === 'string') {

        link.href = uri;
        link.download = filename;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

    } else {

        window.open(uri);

    }
}