
    function cargarDatos() {
        var datos1 = {1:{"nivel":55, "max": 0.8, "min": 0.15}, 2:{"conexiones":[100, 190, 150, 120, 90, 100, 115]}, 3:{"minimos": [100, 190, 110, 215, 150, 190, 120]}};
        var datos2 = {1:{"nivel":75, "max": 0.9, "min": 0.2}, 2:{"conexiones":[200, 190, 180, 150, 190, 200, 215]}, 3:{"minimos": [200, 195, 170, 155, 185, 190, 220]}};
        var datos3 = {1:{"nivel":41, "max": 0.9, "min": 0.3}, 2:{"conexiones":[100, 190, 150, 120, 90, 100, 115]}, 3:{"minimos": [100, 190, 200, 215, 185, 190, 120]}};
        var datos4 = {1:{"nivel":87, "max": 0.87, "min": 0.2}, 2:{"conexiones":[100, 190, 150, 120, 90, 100, 115]}, 3:{"minimos": [100, 200, 110, 145, 150, 185, 120]}};
        var datos5 = {1:{"nivel":62, "max": 0.85, "min": 0.1}, 2:{"conexiones":[100, 190, 150, 120, 90, 100, 115]}, 3:{"minimos": [185, 190, 150, 154, 200, 190, 120]}};
        var datos = {1 : datos1, 2 : datos2, 3 : datos3, 4 : datos4, 5 : datos5};

        var i = 5;
        nwids = i;
        
        while (e <= nwids) {
            posiciones[e] = 1;
            e++;
        }

        var c = 1;
        while(c <= i){
            generarWid(c, datos);
            c++;
        }

    }


    function transicion(widget) {

        var posicion = posiciones[widget];
        
        if (posicion <= 4) {
            
            document.getElementById("widVal"+widget+"").style.bottom = 'calc('+posicion+' * 20em)';
            document.getElementById("widConex"+widget+"").style.bottom = 'calc('+posicion+' * 20em)';
            document.getElementById("widMin"+widget+"").style.bottom = 'calc('+posicion+' * 20em)';
            document.getElementById("widAla"+widget+"").style.bottom = 'calc('+posicion+' * 20em)';
            posiciones[widget] = posicion +1;

        }
        if (posicion == 4) {
            
            document.getElementById("widVal"+widget+"").style.bottom = '0em';
            document.getElementById("widConex"+widget+"").style.bottom = '0em';
            document.getElementById("widMin"+widget+"").style.bottom = '0em';
            document.getElementById("widAla"+widget+"").style.bottom = '0em';
            posiciones[widget] = 1;
        }
        
    }

    function mostrarResumen() {
        document.getElementById("resumen").style.opacity = '100%';
    }

    function efectoListaAlarmas1(elem){
        elem.style.marginBottom = '2%';
    }

    function efectoListaAlarmas2(elem){
        elem.style.marginBottom = '1%';
    }


    function generarWid(widget, datos) {


        var chartDom = document.getElementById("widVal"+widget+"");
        var grafiGauge = echarts.init(chartDom);

        var gauge = {
            title: {
                text: 'Nivel',
                textStyle: {
                    left: "center",
                    top: "center",
                    fontSize: 15,
                    color: '#FFFFFF'
                }
            },
            tooltip: {
                formatter: '{b} : {c} L'
            },
            series: [
                {
                name: 'Nivel',
                type: 'gauge',
                progress: {
                    show: false,
                    width: 8
                },    
                detail: {
                    formatter: '{value}',
                    valueAnimation: true,
                    color: '#FFFF',
                },
                axisLabel: {
                    distance: 15,
                    color: '#FFFF',
                    fontSize: 15
                },
                axisLine: {
                    lineStyle: {
                    width: 10,
                    color: [
                        [datos[widget][1]["min"], '#d0ff00'],
                        [datos[widget][1]["max"], '#00fff7'],
                        [1, '#ff0000']
                    ]
                    }
                },
                axisTick: {
                    show: true,
                    lineStyle:{
                        color:'#ffff'
                    }
                },
                splitLine: {
                    length: 10,
                    lineStyle: {
                    width: 2,
                    color: '#FFFF'
                    }
                },
                color: '#ffffff',
                data: [
                    {
                    value: datos[widget][1]["nivel"],
                    name: 'Nivel'
                    }
                ]
                }
            ]
        };


        var chartDom2 = document.getElementById('widConex' +widget+'');
        var grafiConex = echarts.init(chartDom2);
        var conex = {
                        title: {
                            text: 'Calidad de Conexion',
                            textStyle: {
                                left: "center",
                                top: "center",
                                fontSize: 15,
                                color: '#FFFFFF'
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
                            data: datos[widget][2]["conexiones"]
                            },
                        ]
        };


        var chartDom3 = document.getElementById('widMin'+widget+'');
        var grafiMin = echarts.init(chartDom3);
        var min = {
                        title: {
                            text: 'Minino Acumulado Nocturno',
                            textStyle: {
                                left: "center",
                                top: "center",
                                fontSize: 15,
                                color: '#FFFFFF'
                            }
                        },
                        tooltip: {
                            trigger: 'axis',
                            axisPointer: {
                            type: 'shadow'
                            }
                        },
                        xAxis: {
                            type: 'category',
                            data: ['Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab', 'Dom']
                        },
                        yAxis:{

                        },
                        series : [
                            {
                            name: 'mínimo acumulado',
                            data: datos[widget][3]["minimos"],
                            type: 'bar',
                            color: 'rgb(1, 168, 184)'
                            }
                            // {
                            // name: 'maximo',
                            // data: [200, 200, 200, 200, 200, 200, 200],
                            // type: 'line',
                            // color: '#ff7700'
                            // },
                            // {
                            // name: 'minimo',
                            // data: [10, 10, 10, 10, 10, 10, 10],
                            // type: 'line',
                            // color: '#ff7700'
                            // }

                        ]
        };

        gauge && grafiGauge.setOption(gauge, true);
        conex && grafiConex.setOption(conex, true);
        min && grafiMin.setOption(min, true);

    }