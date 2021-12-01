
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


function imprimir() {
    html2canvas(document.querySelector("#grafica")).then(canvas => {
    document.body.appendChild(canvas)
    });
}


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
    renderGrafico(tipo, datosR);
}


function renderGrafico(tipo,datosR) {

    var chartDom = document.getElementById('grafica');
    var grafico = echarts.init(chartDom);
    var option;

    if(tipo =="barra"){

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
            xAxis: [
                {
                type: 'category',
                data: ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo']
                }
            ],
            yAxis: [
                {
                type: 'value'
                }
            ]
            };
        
        var series = []
        
        datosR.forEach(function(valores, index, array){
            
            var datorender = {
                name: "dato "+ index,
                type: 'bar',
                smooth: true,
                emphasis: {
                    focus: 'series'
                },
                data: valores
                }

            
            series.push(datorender);
            
        });
        option['series'] = series;
        console.log(option);
    }
    if(tipo == "linea"){
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
            xAxis: [
                {
                type: 'category',
                data: ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo']
                }
            ],
            yAxis: [
                {
                type: 'value'
                }
            ]
            };
        
        var series = []
        
        datosR.forEach(function(valores, index, array){
            
            var datorender = {
                name: "dato "+ index,
                type: 'line',
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


    option && grafico.setOption(option, true);
    
    
    document.getElementById('conPrincipal').onmouseover = function(){
        grafico.resize();
    }
    document.getElementById('menuIzq').onmouseover = function(){
        grafico.resize();
    }
    
}

