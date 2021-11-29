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
        document.getElementById("secError").style.opacity = '0%';
        
    }
}

function graficoConex() {
    var i = 0;
    var e = 7;
    var datos = [];

    while (i < e) {
        var dato = Math.random() * (10 - 1) + 1;
        datos.push(dato);
        i++;
    }
    
    var chartDom = document.getElementById('graficoConexion');
    var myChart = echarts.init(chartDom);
    var option;

    option = {title: {
        text: 'Calidad de Conexión',
        textStyle: {
            left: "center",
            top: "center",
            fontSize: 15,
            color: 'rgb(1, 168, 184)'
        }
    },
    tooltip: {
        trigger: 'axis',
        axisPointer: {
            type: 'line',
            label: {
                backgroundColor: '#6a7985 0.1'
            }
        }
    },
    xAxis: {
        type: 'category',
        data: ['Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab', 'Dom'],
        color: 'rgb(1, 168, 184)'
        
    },
    yAxis: {
        
    },
    series : [
        {
        color: 'rgb(1, 168, 184)',
        name: 'Calidad',
        type: 'line',
        stack: 'Total',
        areaStyle: {},
        emphasis: {
            focus: 'series'
        },
        data: datos
        },
    ]
    };

    option && myChart.setOption(option);
    
}