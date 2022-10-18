//por algun motivo hay un overflow x de tipo scroll al hacer hover sobre un chart por primera vez
var widsAnalogLista = [];
var datosDigi = Array();
var datosAnalog = Array();
var consignas = Array();
var bombas = Array();
var todoDato = Array();
var todoTrends = Array();
var tagsAcumulados = Array();
var listaCon = Array();

sessionStorage.setItem("tagViejo", null);
//actualizar la info de la seccion estacion
function actualizar(id_estacion) {
  $(document).ready(function () {
    $.ajax({
      type: "POST",
      url: "/Aquando.com/A_Estacion.php",
      data: {
        opcion: "actualizar",
        estacion: id_estacion,
        tipo: "todos",
      },
      success: function (datos) {
        filtrarDatos(datos);
      },
      error: function () {
        console.log("error");
      },
      dataType: "json",
    });
  });
}
//obiene los trends de los ultimos 7 dias de los tags analógicos
function trendsTags() {
  var listaTags = datosAnalog.concat(tagsAcumulados);
  var arrTags = JSON.stringify(listaTags);
  var id_estacion = estacion;
  $(document).ready(function () {
    $.ajax({
      type: "POST",
      data: {
        opcion: "trends",
        estacion: id_estacion,
        arrTags: arrTags,
        tipo: "todos",
      },
      url: "/Aquando.com/A_Estacion.php",
      success: function (trends) {
        montarWidgetsAnalogicos();
        todoTrends = trends;
        montarWidgetsDigi();
      },
      error: function () {
        console.log("error en las trends");
      },
      dataType: "json",
    });
  });
}
//divide los ultimos datos de la estacion según el tipo de señal
function filtrarDatos(datos) {
  var tagsBombas = Array();
  for (var indexDato in datos) {
    if (!datos[indexDato]["nombre_tag"].includes("Comunicacion")) {
      if (!datos[indexDato]["nombre_tag"].includes("Bomba")) {
        if (
          datos[indexDato]["valor"] == "t" ||
          datos[indexDato]["valor"] == "f"
        ) {
          datosDigi[indexDato] = datos[indexDato];
        } else {
          if (
            datos[indexDato]["nombre_tag"].includes("Acumulado") &&
            !datos[indexDato]["nombre_tag"].includes("Consigna")
          ) {
            tagsAcumulados[indexDato] = datos[indexDato];
          } else {
            if (datos[indexDato]["nombre_tag"].includes("Consigna")) {
              consignas[indexDato] = datos[indexDato];
            } else {
              if (!datos[indexDato]["nombre_tag"].includes("Acumulado")) {
                datosAnalog[indexDato] = datos[indexDato];
                datosAnalog[indexDato]["consignas"] = [];
              }
            }
          }
        }
      } else {
        tagsBombas[datos[indexDato]["id_tag"]] = datos[indexDato];
      }
    }
  }
  for (var index in datosAnalog) {
    for (var con in consignas) {
      if (
        consignas[con]["nombre_tag"].includes(datosAnalog[index]["nombre_tag"])
      ) {
        datosAnalog[index]["consignas"].push(consignas[con]);
      }
    }
  }
  todoDato["tags_digitales"] = datosDigi;
  todoDato["tags_analogicos"] = datosAnalog;
  todoDato["tags_acu"] = tagsAcumulados;
  todoDato["consignas"] = consignas;
  var nBombas = 0;
  for (var bTag in tagsBombas) {
    if (!tagsBombas[bTag]["nombre_tag"].includes("Bombas")) {
      if (
        tagsBombas[bTag]["valor"] == "t" ||
        tagsBombas[bTag]["valor"] == "f"
      ) {
        nBombas++;
        var nombre = "Bomba " + nBombas;
        bombas[nombre] = [];
        for (var BTTag in tagsBombas) {
          if (tagsBombas[BTTag]["nombre_tag"].includes("Bomba " + nBombas)) {
            bombas[nombre].push(tagsBombas[BTTag]);
          }
        }
      }
    }
  }
  todoDato["bombas"] = bombas;
  trendsTags();
  consignasAltBd();
}
//montar widgets de tags digitales
function montarWidgetsDigi() {
  var seccionDigital = document.getElementById("seccionInf");
  var widg = "";
  var msg;
  var iconoAlarma = "";
  seccionDigital.innerHTML = "";
  for (var indexDato in datosDigi) {
    var tf = datosDigi[indexDato]["valor"];
    if (datosDigi[indexDato]["unidad"] == "i") {
      if (tf == "t") {
        tf = "f";
      } else {
        tf = "t";
      }
    }
    //GENERICO
    if (tf == "f") {
      iconoAlarma = '<i style="color:yellowgreen" class="fas fa-check"></i>';
      msg = "BIEN";
    } else {
      iconoAlarma =
        '<i style="color:tomato" class="fas fa-exclamation-triangle parpadeante"></i>';
      msg = "ALARMA";
    }
    //PUERTAS
    if (datosDigi[indexDato]["nombre_tag"].includes("Puerta")) {
      if (tf == "t") {
        iconoAlarma = '<i class="fas fa-lock-open"></i>';
        msg = "ABIERTA";
      } else {
        iconoAlarma = '<i class="fas fa-lock"></i>';
        msg = "CERRADA";
      }
    }
    //varistores
    if (datosDigi[indexDato]["nombre_tag"].includes("Varistores")) {
      if (tf == "f") {
        iconoAlarma =
          '<i style="color:yellowgreen" class="fas fa-shield-alt"></i>';
        msg = "SEGURO";
      } else {
        iconoAlarma =
          '<i style="color:tomato" class="fas fa-bolt parpadeante"></i>';
        msg = "PROBLEMA";
      }
    }
    //red 220
    if (datosDigi[indexDato]["nombre_tag"].includes("Red 220")) {
      if (tf == "f") {
        iconoAlarma = '<i style="color:yellowgreen" class="fas fa-plug"></i>';
        msg = "BIEN";
      } else {
        iconoAlarma =
          '<i style="color:tomato" class="fas fa-plug parpadeante"></i>';
        msg = "PROBLEMA";
      }
    }
    //bateria
    if (datosDigi[indexDato]["nombre_tag"].includes("Bateria")) {
      if (tf == "f") {
        iconoAlarma =
          '<i style="color:yellowgreen" class="fas fa-battery-full"></i>';
        msg = "BIEN";
      } else {
        iconoAlarma =
          '<i style="color:tomato" class="fas fa-battery-empty parpadeante"></i>';
        msg = "PROBLEMA";
      }
    }
    //ELECTROVALVULAS
    if (datosDigi[indexDato]["nombre_tag"].includes("Electrovalvula")) {
      if (tf == "t") {
        iconoAlarma =
          '<i style="color:yellowgreen" class="fas fa-power-off"></i>';
        msg = "ABIERTA";
      } else {
        iconoAlarma = '<i style="color:tomato" class="fas fa-power-off"></i> ';
        msg = "CERRADA";
      }
    }
    widg =
      '<div class="widDigi"><div id="digiWidTitulo" maxlength="20">' +
      datosDigi[indexDato]["nombre_tag"] +
      '</div><div id="digiWidMensaje" maxlength="20">' +
      iconoAlarma +
      '</div><span class="tooltiptext">' +
      msg +
      "</span></div>";
    seccionDigital.innerHTML += widg;
  }
}
//montar widgets analógicos
function montarWidgetsAnalogicos() {
  var seccionAnalog = document.getElementById("estacionDer");
  var seccionAcu = document.getElementById("estacionCentro");
  seccionAnalog.innerHTML = "";
  seccionAcu.innerHTML = "";
  for (var indexDato in tagsAcumulados) {
    if (tagsAcumulados[indexDato]["nombre_tag"].includes("Dia")) {
      var widgInicio = '<div class="widAna">';
      var widgFin = "";
      var widgInfo =
        '<div class="widAnaInfo"><div class="widAnaInfoPrin"><p style=color:rgb(39,45,79);font-weight:bold>' +
        tagsAcumulados[indexDato]["nombre_tag"] +
        " (" +
        tagsAcumulados[indexDato]["unidad"] +
        "): " +
        tagsAcumulados[indexDato]["valor"] +
        "</p> " +
        "</div>";
      var consi = "";
      var widgSec = "";
      consi +=
        '<div class="contador" id="contador' +
        tagsAcumulados[indexDato]["nombre_tag"].replace(/\s+/g, "") +
        '" class="widAnaInfoSec"><div class="panelNegro" id="panelNegro' +
        tagsAcumulados[indexDato]["nombre_tag"].replace(/\s+/g, "") +
        '"></div><div class="panelRojo" id="panelRojo' +
        tagsAcumulados[indexDato]["nombre_tag"].replace(/\s+/g, "") +
        '"></div></div>';
      consi += "</div>";
      var widgGraf =
        '<div class="widAnaGraf" id="chart' +
        tagsAcumulados[indexDato]["nombre_tag"].replace(/\s+/g, "") +
        '"></div>';
      var widget = widgInicio + widgInfo + widgSec + consi + widgGraf + widgFin;
      seccionAnalog.innerHTML += widget;
    }
  }
  for (var indexDato in datosAnalog) {
    var widgInicio = '<div class="widAna">';
    var widgFin = "";
    var widgInfo =
      '<div class="widAnaInfo"><div class="widAnaInfoPrin"><p style=font-weight:bold;margin-bottom:-1.5em;color:rgb(39,45,79)>' +
      datosAnalog[indexDato]["nombre_tag"] +
      " (" +
      datosAnalog[indexDato]["unidad"] +
      "): " +
      datosAnalog[indexDato]["valor"] +
      "</p> ";
    widgInfo += "</div>";
    var consi = "";
    var widgSec = "";
    consi +=
      '<div id="gau' +
      datosAnalog[indexDato]["nombre_tag"].replace(/\s+/g, "") +
      '" class="widAnaInfoSec"></div>';
    consi += "</div>";
    var widgGraf =
      '<div class="widAnaGraf" id="chart' +
      datosAnalog[indexDato]["nombre_tag"].replace(/\s+/g, "") +
      '"></div>';
    var widget = widgInicio + widgInfo + widgSec + consi + widgGraf + widgFin;
    seccionAcu.innerHTML += widget;
  }
  var widsBombas = "";
  for (var bomba in bombas) {
    var widTiempo = "";
    var widArranques = "";
    var widDefecto = "";
    var bombaNombre = "<div id='widBombaNombre'>";
    var bombaEstado = "<div id='widBombaEstado'>";
    if (bombas[bomba].length > 0) {
      //nombre
      bombaNombre += bomba + "</div>";
      //estado
      if (bombas[bomba][0]["valor"] == "t") {
        bombaEstado +=
          '<i style="color:gray;" class="fas fa-cog rotante"></i></div>';
      }
      if (bombas[bomba][0]["valor"] == "f") {
        bombaEstado +=
          '<i style="color:darkorange;" class="fas fa-pause"></i></div>';
      }
      for (var index in bombas[bomba]) {
        //tiempos
        if (bombas[bomba][index]["nombre_tag"].includes("Tiempo")) {
          var num = bombas[bomba][index]["valor"];
          var dias = num / 86400;
          var rdias = Math.floor(dias);
          var horas = (dias - rdias) * 24;
          var rhoras = Math.floor(horas);
          var minutos = (horas - rhoras) * 60;
          var rminutos = Math.floor(minutos);
          // var tiempo = rdias + " Dias, " + rhoras + " horas y " + rminutos + " minutos.";
          // widTiempo = "<b>Tiempo en marcha: </b>" + tiempo + "<br>";
          var contador =
            "<table id='contadorBomba'><tr><th colspan=3>Tiempo total en marcha</th></tr><tr><td class='bombaDias'>" +
            rdias +
            " Dias</td><td class='bombaHoras'>" +
            rhoras +
            " Horas</td><td class='bombasMins'>" +
            rminutos +
            " Minutos</td></tr></table>";
          widTiempo = contador;
        }
        //arranques
        else if (bombas[bomba][index]["nombre_tag"].includes("Arranques")) {
          widArranques =
            "<b>Veces en marcha: </b>" + bombas[bomba][index]["valor"] + "<hr>";
        }
        //defecto
        else if (bombas[bomba][index]["nombre_tag"].includes("Defecto")) {
          if (bombas[bomba][index]["valor"] == "t") {
            widDefecto =
              "<div id='bombaDefecto'><i style='color:tomato' class='fas fa-exclamation-triangle parpadeante'></i></div>";
          } else {
            widDefecto =
              "<div id='bombaDefecto'><i style='color:yellowgreen' class='fas fa-shield-alt'></i></div>";
          }
        }
        //orden? ->nada
        else if (bombas[bomba][index]["nombre_tag"].includes("Orden")) {
        }
      }
      var bombaInf =
        "<div id='widBombaInf'>" +
        widDefecto +
        widArranques +
        widTiempo +
        "</div>";
      var widBomba =
        "<div id='widBomba'>" + bombaNombre + bombaInf + bombaEstado + "</div>";
      widsBombas += widBomba;
    }
  }
  seccionAnalog.innerHTML += widsBombas;
  montarGraficosWidget();
  controlMobile();
}
//render de los graficos
//hay que hacer el captador con resize
//wid de deposito?
function montarGraficosWidget() {
  for (var tag in tagsAcumulados) {
    var nombreDato = tagsAcumulados[tag]["nombre_tag"].replace(/\s+/g, "");
    if (nombreDato.includes("Dia") && !nombreDato.includes("Delta")) {
      var chartDom2 = document.getElementById("chart" + nombreDato);
      var grafTrend = echarts.init(chartDom2);
      var valores = [];
      var fechas = [];
      if (todoTrends[tag] !== undefined) {
        valores.push(todoTrends[tag]["max"]);
        fechas.push(todoTrends[tag]["fecha"]);
      }
      if (valores.length > 0) {
        document.getElementById("panelRojo" + nombreDato).innerHTML =
          "hoy: " + valores[0][0];
      } else {
        document.getElementById("panelRojo" + nombreDato).innerHTML =
          "sin trends";
      }
      optionChart = {
        grid: {
          left: "2%",
          right: "4%",
          top: "12%",
          bottom: "2%",
          containLabel: true,
        },
        tooltip: {
          trigger: "axis",
          textStyle: {
            fontStyle: "bold",
            fontSize: 10,
          },
          axisPointer: {
            axis: "x",
            snap: true,
            offset: 0,
            type: "line",
            label: {
              formatter: "fecha y hora: {value}",
              fontStyle: "bold",
            },
          },
        },
        xAxis: {
          inverse: false,
          show: true,
          type: "category",
          data: fechas[0],
        },
        yAxis: {
          type: "value",
        },
        series: [
          {
            name: tagsAcumulados[tag]["nombre_tag"],
            data: valores[0],
            type: "bar",
            itemStyle: {
              color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                { offset: 0, color: "yellowgreen" },
                { offset: 1, color: "darkseagreen" },
              ]),
            },
            emphasis: {
              itemStyle: {
                color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                  { offset: 0, color: "yellowgreen" },
                  { offset: 1, color: "darkseagreen" },
                ]),
              },
            },
            symbol: "none",
            smooth: false,
          },
        ],
      };
      widsAnalogLista.push([grafTrend]);
      optionChart && grafTrend.setOption(optionChart, true);
    } else {
      if (!nombreDato.includes("Delta")) {
        if (
          document.getElementById(
            "panelNegro" + nombreDato.replace(/\s+/g, "") + "Dia"
          )
        ) {
          document.getElementById(
            "panelNegro" + nombreDato.replace(/\s+/g, "") + "Dia"
          ).innerHTML = "Total: " + todoDato["tags_acu"][tag]["valor"];
        } else {
          document.getElementById(
            "panelNegro" + nombreDato.replace(/\s+/g, "") + "Dia"
          ).innerHTML = "sin datos de la señal";
        }
      }
    }
  }
  for (var tag in datosAnalog) {
    var optionGauge;
    var r_max = 10;
    var r_min = 0;
    if (datosAnalog[tag]["r_max"] != undefined) {
      r_max = parseFloat(datosAnalog[tag]["r_max"]);
    }
    if (datosAnalog[tag]["r_min"] != undefined) {
      r_min = parseFloat(datosAnalog[tag]["r_min"]);
    }
    var nombreDato = datosAnalog[tag]["nombre_tag"].replace(/\s+/g, "");
    //gauge para niveles, cloro, caudal
    var chartDom = document.getElementById("gau" + nombreDato);
    var chartDom2 = document.getElementById("chart" + nombreDato);
    var gauge = echarts.init(chartDom);
    var grafTrend = echarts.init(chartDom2);
    var valor = datosAnalog[tag]["valor"];
    var maximo = 10;
    var minimo = 0;
    var titu = "";
    if (datosAnalog[tag]["consignas"].length >= 1) {
      maximo = datosAnalog[tag]["consignas"][0]["valor"];
      titu += "Max: " + parseFloat(maximo).toFixed(2) + "\n";
      maximo = maximo / r_max;
    }
    if (datosAnalog[tag]["consignas"].length == 2) {
      minimo = datosAnalog[tag]["consignas"][1]["valor"];
      titu += "Min: " + parseFloat(minimo).toFixed(2);
      if (r_min != 0) {
        minimo = minimo / r_min;
      } else {
        minimo = 0;
      }
    }
    optionGauge = {
      title: {
        left: "left",
        text: titu,
        textStyle: {
          fontStyle: "bold",
          fontSize: 8,
        },
      },
      grid: {
        left: "0%",
        right: "0%",
        top: "8%",
        bottom: "0%",
        containLabel: true,
      },
      series: [
        {
          name: nombreDato,
          type: "gauge",
          itemStyle: {
            color: "rgb(1, 168, 184)",
          },
          progress: {
            show: false,
          },
          axisLine: {
            show: true,
            lineStyle: {
              width: 5,
              color: [
                [minimo, "tomato"],
                [maximo, "rgb(39, 45, 79)"],
                [1, "tomato"],
              ],
            },
          },
          axisTick: {
            show: true,
            length: 4,
            distance: 2,
            splitNumber: 1,
          },
          axisLabel: {
            show: false,
          },
          splitLine: {
            show: false,
          },
          pointer: {
            // icon: 'rect',
            length: "80%",
            width: 4,
          },
          max: r_max,
          min: r_min,
          detail: {
            show: true,
            valueAnimation: true,
            formatter: "{value}",
            fontSize: 12,
          },
          data: [
            {
              value: valor,
            },
          ],
        },
      ],
    };
    var valores = [];
    var fechas = [];
    if (todoTrends[tag] != null && todoTrends[tag] != "undefined") {
      valores.push(todoTrends[tag]["max"]);
      fechas.push(todoTrends[tag]["fecha"]);
    }
    optionChart = {
      grid: {
        left: "2%",
        right: "4%",
        top: "12%",
        bottom: "2%",
        containLabel: true,
      },
      tooltip: {
        trigger: "axis",
        textStyle: {
          fontStyle: "bold",
          fontSize: 12,
        },
        axisPointer: {
          axis: "x",
          snap: true,
          offset: 0,
          type: "line",
          label: {
            formatter: "fecha y hora: {value}",
            fontStyle: "bold",
          },
        },
      },
      xAxis: {
        inverse: false,
        show: true,
        type: "category",
        data: fechas[0],
      },
      yAxis: {
        name: datosAnalog[tag]["nombre_tag"],
        type: "value",
      },
      series: [
        {
          name: datosAnalog[tag]["nombre_tag"],
          data: valores[0],
          type: "line",
          lineStyle: {
            width: 0,
          },
          areaStyle: {
            show: true,
            color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
              {
                offset: 0,
                color: "rgb(1, 168, 184)",
              },
              {
                offset: 1,
                color: "rgb(39, 45, 79)",
              },
            ]),
          },
          symbol: "none",
          smooth: false,
        },
      ],
    };
    widsAnalogLista.push([gauge, grafTrend]);
    optionGauge && gauge.setOption(optionGauge, true);
    optionChart && grafTrend.setOption(optionChart, true);
    document.getElementsByClassName("btnOpci")[0].style.display = "block";
    document.getElementsByClassName("btnOpci")[1].style.display = "block";
  }
  $("#menuIzq").bind("widthChange", function () {
    if (widsAnalogLista != undefined) {
      for (var index in widsAnalogLista) {
        widsAnalogLista[index][0].resize();
        if (widsAnalogLista[index].length > 1) {
          widsAnalogLista[index][1].resize();
        }
      }
    }
  });
}
//muestra en caso de tenerla, la imagen correspondiente a la estacion
function fotoEstacion(id_estacion) {
  $(document).ready(function () {
    $.ajax({
      type: "POST",
      data: {
        opcion: "foto",
        estacion: id_estacion,
      },
      url: "/Aquando.com/A_Estacion.php?",
      success: function (foto) {
        var ima;
        if (foto != "") {
          ima =
            'linear-gradient(to left, rgba(255,255,255,0.99),rgba(255,255,255,0)),url("data:image/jpg;base64,' +
            foto +
            '")';
          document.getElementById("seccionFoto").style.backgroundImage = ima;
          document.getElementById("seccionFoto").style.backgroundSize = "cover";
        }
      },
      error: function () {
        console.log("error");
      },
    });
  });
}
//funcion que establece a los widgets un control para alternar los trends y la info
//solo se ejecuta si la pantalla es menor a 600px
function controlMobile() {
  if (screen.width < 600) {
    var a = document.getElementsByClassName("widAnaInfo");
    for (let i = 0; i < a.length; i++) {
      a[i].onclick = function () {
        a[i].style.width = "0%";
        a[i].style.display = "none";
        document.getElementsByClassName("widAnaGraf")[i].style.width = "100%";
        document.getElementsByClassName("widAnaGraf")[i].style.display =
          "block";
        if (widsAnalogLista != undefined) {
          for (var index in widsAnalogLista) {
            widsAnalogLista[index][0].resize();
            if (widsAnalogLista[index].length > 1) {
              widsAnalogLista[index][1].resize();
            }
          }
        }
      };
    }
    var b = document.getElementsByClassName("widAnaGraf");
    for (let i = 0; i < b.length; i++) {
      b[i].onclick = function () {
        b[i].style.width = "0%";
        b[i].style.display = "none";
        document.getElementsByClassName("widAnaInfo")[i].style.width = "100%";
        document.getElementsByClassName("widAnaInfo")[i].style.display =
          "block";
        if (widsAnalogLista != undefined) {
          for (var index in widsAnalogLista) {
            widsAnalogLista[index][0].resize();
            if (widsAnalogLista[index].length > 1) {
              widsAnalogLista[index][1].resize();
            }
          }
        }
      };
    }
  }
}

//experimental y provisional
function consignasAltBd() {
  $(document).ready(function () {
    $.ajax({
      type: "POST",
      url: "/Aquando.com/A_Ajustes.php",
      data: {
        estacion: nestacion,
        opcion: "con",
      },
      success: function (datos) {
        var infoConsig = [];
        for (var i = 0; i < datos.length; i++) {
          infoConsig.push({
            NW: datos[i]["Nombre_WIT"].trim(),
            RW: datos[i]["Recurso_wit"].trim(),
            NT: datos[i]["nombre_tag"],
          });
        }
        listaCon = infoConsig;
      },
      error: function (e) {
        console.log("error");
      },
      dataType: "json",
    });
  });
}

function leerValorConsigna(ref, nombre) {
  $(document).ready(function () {
    $.ajax({
      type: "POST",
      url: "/Aquando.com/A_Ajustes.php",
      data: {
        ref: ref,
        opcion: "det",
      },
      success: function (datos) {
        var estado = "desconocido";
        if (datos["ValueWriteStatus"] == 0) {
          estado = "Confirmado";
        } else {
          estado = "Pendiente de comunicar";
        }
        datosConsig = {
          valor: datos["ValueReadData"].replace(/ /g, ""),
          estado: estado,
          nombre: nombre,
        };
        var zona = document.getElementById("ajustesDisplay");
        zona.innerHTML = "";
        var ajustes = "";
        ajustes +=
          "<h4><b>Modificar consignas</b><i style='font-size:115%' class='far fa-bell'></i></h4><hr>";
        ajustes +=
          "<p>Valor actual de <b>" +
          datosConsig["nombre"] +
          "</b>: " +
          datosConsig["valor"] +
          " </p>";
        ajustes += "<p>Estado: <b>" + datosConsig["estado"] + "</b></p><hr>";
        ajustes +=
          "<p><b>Nuevo valor: <input type='number' id='inputAjustes' value=" +
          datosConsig["valor"] +
          " ></b></p>";
        ajustes +=
          "<button id=btnAceptarConsigna>Aceptar <i id='iconoAceptarConsigna' onclick='' class='fas fa-check'></i></button>";
        ajustes +=
          "<button onclick='ajustes()' id=btnCancelarConsigna>Cancelar <i id='iconoCancelarConsigna' class='fas fa-backspace'></i></button>";
        zona.innerHTML += ajustes;
      },
      error: function (e) {
        console.log("error");
      },
      dataType: "json",
    });
  });
}

function modificarConsignas(ref, valor) {
  $(document).ready(function () {
    $.ajax({
      type: "POST",
      url: "/Aquando.com/A_Ajustes.php",
      data: {
        ref: ref,
        opcion: "mod",
      },
      success: function (datos) {
        console.log("exito?");
      },
      error: function (e) {
        console.log("error");
      },
      dataType: "json",
    });
  });
}

//AJUSTES DE CONSIGNAS VIEJOS
//muestra u oculta el menu de ajustes de las estaciones
//de momento no lo vamos a implementar
function ajustes() {
  var ajustes = document.getElementById("ajustesEstacion");
  if (ajustes.style.display == "block") {
    ajustes.style.opacity = "0%";
    setTimeout(function () {
      ajustes.style.display = "none";
    }, 200);
  } else {
    ajustes.style.display = "block";
    setTimeout(function () {
      ajustes.style.opacity = "100%";
    }, 200);
    var selec = document.getElementById("listaTags");
    selec.innerHTML = "";
    var lista = "";
    var i = 0;
    var primero;
    for (var index = 0; index < listaCon.length; index++) {
      lista +=
        "<li class=tagEnLista onclick='mostrarAjustesTag(this)' id=" +
        listaCon[index]["NW"] +
        "." +
        listaCon[index]["RW"] +
        " >" +
        listaCon[index]["NT"] +
        "</li>";
      // if (index == 0) {
      //   primero = datosAnalog[indexTag]["id_tag"];
      // }
    }
    selec.innerHTML += lista;
  }
  // mostrarAjustesTag(primero);
}
//funciones para los ajustes. Muestran los tags con consignas modificables de la estación
function mostrarAjustesTag(obj) {
  ref = obj.id.toString();
  var n = obj.innerText;
  var datosConsigna = leerValorConsigna(ref, n);
}
