<?= $this->extend('inicio') ?>
<?= $this->section('content') ?>

<script src ='css/echarts.js'></script>

<main style="padding-left: 3em;">
    <h1 style="margin-left: 1em; color: black">Zona de Gráficos</h1>
    <div style="width: 100%;text-align:center;margin-top:2em;">
        <button class="btn btn-outline-dark me-2 btn-block"style="margin: 0.5em; width: 150px;" value='1' onclick=mostrar(this.value)>
            H General</button>
        <button class="btn btn-outline-dark me-2 btn-block"style="margin: 0.5em; width: 150px;" value='2' onclick=mostrar(this.value)>
            H Particular</button>
        <button class="btn btn-outline-dark me-2 btn-block"style="margin: 0.5em; width: 150px;"value='3' onclick=mostrar(this.value)>
            Estados y Niveles</button>
        <button class="btn btn-outline-dark me-2 btn-block"style="margin: 0.5em; width: 150px;"value='4'disabled onclick=mostrar(this.value)>
            grafico 4</button>
        <button class="btn btn-outline-dark me-2 btn-block"style="margin: 0.5em; width: 150px;"value='5' disabled onclick=mostrar(this.value)>
            grafico 5</button>
    </div>
<div id='graf' style=' padding: 1em;height:30em;background-color:rgba(50, 50, 50);box-shadow: rgb(173, 255, 252, 0.7) 0 0 0px 1px; margin:2em; border-radius:5px; color:black'>
</div>
</main>

<script>
    
    window.onload = function() {
        setInterval(fechaYHora, 1000);
    }
    mostrar('1');
    function mostrar(grafico) {
        var chartDom = document.getElementById('graf');
        var grafi = echarts.init(chartDom);
        
        var option = null;
        if(grafico == '1'){
            option = {
                color: ['rgba(1, 191, 236)', 'rgb(0, 217, 255)', 'rgba(55, 162, 255)', 'rgba(128, 255, 165)', 'rgba(255, 255, 255)'],
                title: {
                    text: 'Históricos General',
                    textStyle: {
                        left: "center",
                        top: "center",
                        fontSize: 20,
                        color: '#FFFFFF'
                    }
                },
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {
                    type: 'cross',
                    label: {
                        backgroundColor: '#6a7985 0.1'
                    }
                    }
                },
                legend: {
                    data: [{
                        name:'dato 1',
                        textStyle:{
                            color:'white',
                            shadowColor: 'rgba(255, 255, 255, 0.5)',
                            shadowBlur: 10,
                            show: true
                        }
                    }, {
                        name:'dato 2',
                        textStyle:{
                            color:'white',
                            shadowColor: 'rgba(255, 255, 255, 0.5)',
                            shadowBlur: 10
                        }
                    }, {
                        name:'dato 3',
                        textStyle:{
                            color:'white',
                            shadowColor: 'rgba(255, 255, 255, 0.5)',
                            shadowBlur: 10
                        }
                    }, {
                        name:'dato 4',
                        textStyle:{
                            color:'white',
                            shadowColor: 'rgba(255, 255, 255, 0.5)',
                            shadowBlur: 10
                        }
                    }, {
                        name:'dato 5',
                        textStyle:{
                            color:'white',
                            shadowColor: 'rgba(255, 255, 255, 0.5)',
                            shadowBlur: 10
                        }
                    }]
                },
                toolbox: {
                    feature: {
                    saveAsImage: {}
                    }
                    
                },
                grid: {
                    left: '3%',
                    right: '4%',
                    bottom: '3%',
                    containLabel: true
                },
                xAxis: [
                    {
                    type: 'category',
                    boundaryGap: false,
                    data: ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo']
                    }
                ],
                yAxis: [
                    {
                    type: 'value'
                    }
                ],
                series: [
                    {
                    name: 'dato 1',
                    type: 'line',
                    stack: 'Total',
                    smooth: false,
                    lineStyle: {
                        width: 0
                    },
                    showSymbol: false,
                    areaStyle: {
                        opacity: 0.8,
                        color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                        {
                            offset: 0,
                            color: 'rgba(0, 0, 0)'
                        },
                        {
                            offset: 1,
                            color: '#20566e'
                        }
                        ])
                    },
                    emphasis: {
                        focus: 'series'
                    },
                    data: [140, 232, 101, 264, 90, 340, 250]
                    },
                    {
                    name: 'dato 2',
                    type: 'line',
                    stack: 'Total',
                    smooth: false,
                    lineStyle: {
                        width: 0
                    },
                    showSymbol: false,
                    areaStyle: {
                        opacity: 0.8,
                        color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                        {
                            offset: 0,
                            color: 'rgba(0, 0, 0)'
                        },
                        {
                            offset: 1,
                            color: 'rgb(0, 217, 255)'
                        }
                        ])
                    },
                    emphasis: {
                        focus: 'series'
                    },
                    data: [120, 282, 111, 234, 220, 340, 310]
                    },
                    {
                    name: 'dato 3',
                    type: 'line',
                    stack: 'Total',
                    smooth: false,
                    lineStyle: {
                        width: 0
                    },
                    showSymbol: false,
                    areaStyle: {
                        opacity: 0.8,
                        color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                        {
                            offset: 0,
                            color: 'rgba(55, 162, 255)'
                        },
                        {
                            offset: 1,
                            color: 'rgba(0, 0, 0)'
                        }
                        ])
                    },
                    emphasis: {
                        focus: 'series'
                    },
                    data: [320, 132, 201, 334, 190, 130, 220]
                    },
                    {
                    name: 'dato 4',
                    type: 'line',
                    stack: 'Total',
                    smooth: false,
                    lineStyle: {
                        width: 0
                    },
                    showSymbol: false,
                    areaStyle: {
                        opacity: 0.8,
                        color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                            {
                            offset: 0,
                            color: 'rgba(128, 255, 165)'
                        },
                        {
                            offset: 1,
                            color: 'rgba(0, 0, 0)'
                        }
                        ])
                    },
                    emphasis: {
                        focus: 'series'
                    },
                    data: [220, 402, 231, 134, 190, 230, 120]
                    },
                    {
                    name: 'dato 5',
                    type: 'line',
                    stack: 'Total',
                    smooth: false,
                    lineStyle: {
                        width: 0
                    },
                    showSymbol: false,
                    label: {
                        show: true,
                        position: 'top'
                    },
                    areaStyle: {
                        opacity: 0.8,
                        color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                            {
                            offset: 0,
                            color: 'rgba(255, 255, 255)'
                        },
                        {
                            offset: 1,
                            color: 'rgba(0, 0, 0)'
                        }
                        ])
                    },
                    emphasis: {
                        focus: 'series'
                    },
                    data: [220, 302, 181, 234, 210, 290, 150]
                    }
                    ]
                };
                    
        }
        if(grafico == '2'){
            option = {
                title: {
                    text: 'Históricos particular',
                    textStyle: {
                        left: "center",
                        top: "center",
                        fontSize: 20,
                        color: '#FFFFFF'
                    }
                },
            xAxis: {
                type: 'category',
                data: ['Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab', 'Dom']
            },
            yAxis: {
                type: 'value'
            },
            series : [
                {
                data: [120, 200, 150, 80, 70, 110, 130],
                type: 'bar',
                color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                        {
                            offset: 0,
                            color: 'rgb(100, 100,100)'
                        },
                        {
                            offset: 1,
                            color: '#20566e'
                            
                        }
                        ]),
                showBackground:true,
                label:true,

                }
            ]
            };
            
        }
        if(grafico == '3'){
            option = {
            title: {
                text: 'Estados y niveles',
                textStyle: {
                    left: "center",
                    top: "center",
                    fontSize: 20,
                    color: '#FFFFFF'
                }
            },
            tooltip: {
                formatter: '{a} <br/>{b} : {c}%'
            },
            series: [
                {
                name: 'PRESION',
                type: 'gauge',
                progress: {
                    show: true,
                    width: 16
                },    
                detail: {
                    formatter: '{value}',
                    valueAnimation: true,
                    color: '#FFFF',
                },
                axisLabel: {
                    distance: 25,
                    color: '#FFFF',
                    fontSize: 15
                },
                axisTick: {
                    show: true,
                    lineStyle:{
                        color:'#ffff'
                    }
                },
                splitLine: {
                    length: 15,
                    lineStyle: {
                    width: 2,
                    color: '#FFFF'
                    }
                },
                min: 0,
                max: 250,
                color : new echarts.graphic.LinearGradient(1, 0, 0, 1, [
                        {
                            offset: 0,
                            color: '#00d9ff'
                        },
                        {
                            offset: 1,
                            color: '#ffff'
                        }
                        ]),
                center: ['19%', '55%'],
                data: [
                    {
                    value: 54,
                    name: 'PRESION'
                    }
                ]
                },
                {
                name: 'NIVEL',
                type: 'gauge',
                progress: {
                    show: true,
                    width: 16
                },    
                detail: {
                    formatter: '{value}',
                    valueAnimation: true,
                    color: '#FFFF',
                },
                axisLabel: {
                    distance: 25,
                    color: '#FFFF',
                    fontSize: 15
                },
                axisTick: {
                    show: true,
                    lineStyle:{
                        color:'#ffff'
                    }
                },
                splitLine: {
                    length: 15,
                    lineStyle: {
                    width: 2,
                    color: '#FFFF'
                    }
                },
                min: 0,
                max: 1000,
                color : new echarts.graphic.LinearGradient(1, 0, 0, 1, [
                        {
                            offset: 0,
                            color: '#00d9ff'
                        },
                        {
                            offset: 1,
                            color: '#ffff'
                        }
                        ]),
                center: ['50%', '50%'],
                data: [
                    {
                    value: 545,
                    name: 'NIVEL'
                    }
                ]
                },
                {
                name: 'BATERIAS',
                type: 'gauge',
                progress: {
                    show: true,
                    width: 16
                },    
                detail: {
                    formatter: '{value}',
                    valueAnimation: true,
                    color: '#FFFF',
                },
                axisLabel: {
                    distance: 25,
                    color: '#FFFF',
                    fontSize: 15
                },
                axisTick: {
                    show: true,
                    lineStyle:{
                        color:'#ffff'
                    }
                },
                splitLine: {
                    length: 15,
                    lineStyle: {
                    width: 2,
                    color: '#FFFF'
                    }
                },
                min: 0,
                max: 100,
                color : new echarts.graphic.LinearGradient(1, 0, 0, 1, [
                        {
                            offset: 0,
                            color: '#00d9ff'
                        },
                        {
                            offset: 1,
                            color: '#ffff'
                        }
                        ]),
                center: ['81%', '55%'],
                data: [
                    {
                    value: 93,
                    name: '%'
                    }
                ]
                }

            ]
            };
        }
        option && grafi.setOption(option, true);
    }
    </script>


<?= $this->endSection() ?>