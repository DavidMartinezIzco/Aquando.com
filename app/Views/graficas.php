<?= $this->extend('inicio') ?>
<?= $this->section('content') ?>
<script src='css/echarts.min.js'></script>
<script src='css/graficas.js'></script>
<script src='css/canvas.js'></script>
<script src='css/canvas.min.js'></script>
<link rel="stylesheet" type="text/css" href="css/graficas.css">
<main id="conPrincipal"
    style="height: 53em; width:100%; border-radius:10px; margin-top:1%">


    <div id="display">
        <div id="zonaControles">

            <div id="panelInfo">
                <h3>info</h3>
            </div>

            <div id="panelOpciones">
                <form>
                    <fieldset>
                        <input type="checkbox" value="1">
                        <label for="1">opcion 1</label>
                        <br>
                        <input type="checkbox" value="2">
                        <label for="2">opcion 2</label>
                        <br>
                        <input type="checkbox" value="3">
                        <label for="3">opcion 3</label>
                    </fieldset>

                    <hr>
                    <fieldset>
                        <input type="radio" name="g2" value="4">
                        <label for="3">opcion 4</label>
                        <br>
                        <input type="radio" name="g2" value="5">
                        <label for="3">opcion 5</label>
                        <br>
                        <input type="radio" name="g2" value="6">
                        <label for="6">opcion 6</label>
                    </fieldset>
                    <hr>
                    <label for="opciones">mas opciones:</label>
                    <select class="controlSel" id="opciones" name="opciones">
                        <option value="7">Opción 7</option>
                        <option value="8">Opción 8</option>
                        <option value="9">Opción 9</option>
                        <option value="10">Opción 10</option>
                    </select>
                    
                </form>
                    <button id="btnControl" style="background-color: yellowgreen;" value="aplicar" name="btnControl">aplicar</button>
                    <button id="btnControl" type="reset" onclick=limpiar() style="background-color: tomato;" value="reset" name="btnControlReset">reset</button>
                    <button id="btnControl" style="background-color: darkseagreen;" value="print" onclick="imprimir()" name="btnControlPrint"><i class="fas fa-print"></i></button>
                
            </div>

        </div>

        <div id="zonaGraficos">
            <div id="grafica" style="width: 100%; height: 100%; border-radius:10px">
               <script>
                   var chartDom = document.getElementById('grafica');
    var grafico = echarts.init(chartDom);
    var option;
    //codigo del gráfico
    option = {

        color: ['rgba(1, 191, 236)', 'rgb(0, 217, 255)', 'rgba(55, 162, 255)', 'rgba(128, 255, 165)',
            'rgba(255, 255, 255)'
        ],
        title: {
            text: 'Gráfica Custom',
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
                name: 'dato 1',
                textStyle: {
                    color: 'white',
                    shadowColor: 'rgba(255, 255, 255, 0.5)',
                    shadowBlur: 10,
                    show: true
                }
            }, {
                name: 'dato 2',
                textStyle: {
                    color: 'white',
                    shadowColor: 'rgba(255, 255, 255, 0.5)',
                    shadowBlur: 10
                }
            }, {
                name: 'dato 3',
                textStyle: {
                    color: 'white',
                    shadowColor: 'rgba(255, 255, 255, 0.5)',
                    shadowBlur: 10
                }
            }, {
                name: 'dato 4',
                textStyle: {
                    color: 'white',
                    shadowColor: 'rgba(255, 255, 255, 0.5)',
                    shadowBlur: 10
                }
            }, {
                name: 'dato 5',
                textStyle: {
                    color: 'white',
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
        xAxis: [{
            type: 'category',
            boundaryGap: false,
            data: ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo']
        }],
        yAxis: [{
            type: 'value'
        }],
        series: [{
                name: 'dato 1',
                type: 'line',
                stack: 'total',
                smooth: true,
                lineStyle: {
                    width: 0
                },
                showSymbol: false,
                areaStyle: {
                    opacity: 0.8,
                    color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
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
                smooth: true,
                lineStyle: {
                    width: 0
                },
                showSymbol: false,
                areaStyle: {
                    opacity: 0.8,
                    color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
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
                smooth: true,
                lineStyle: {
                    width: 0
                },
                showSymbol: false,
                areaStyle: {
                    opacity: 0.8,
                    color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
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
                smooth: true,
                lineStyle: {
                    width: 0
                },
                showSymbol: false,
                areaStyle: {
                    opacity: 0.8,
                    color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
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
                smooth: true,
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
                    color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
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
               </script>
        </div>

        </div>
    </div>

</main>
    <!---alarmas--->
    <table id="alarmasSur">


    </table>

<script>
    window.onload = function () {
        actualizarMini();
        
        setInterval(fechaYHora, 1000);
        setInterval(actualizarMini, 3000);
        
        setInterval(comprobarTiempo, 1000);
    }
</script>


<?= $this->endSection() ?>