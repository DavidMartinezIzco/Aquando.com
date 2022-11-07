//saca una captura de las alarmas
function imprimir() {
  document.getElementsByName("btnControlPrint")[0].innerText = "cargando...";
  document.getElementById("informesSur").style.overflowY = "unset";
  pasarAPDF();
  document.getElementById("informesSur").style.overflowY = "scroll";
  setTimeout(() => {
    document.getElementsByName("btnControlPrint")[0].innerHTML =
      '<i class="fas fa-print"></i>';
  }, 4000);
}
//descarga la captura de las alarmas
function guardar(uri, filename) {
  var link = document.createElement("a");
  if (typeof link.download === "string") {
    link.href = uri;
    link.download = filename;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  } else {
    window.open(uri);
  }
}
//crea objeto CSV a aprtir del informe
function exportarCSV() {
  tipo = document.querySelector('input[name="radInforme"]:checked').value;
  var tipoInf = "";
  if (tipo == "cau") {
    tipoInf = "Caudales";
  }
  if (tipo == "niv") {
    tipoInf = "Niveles";
  }
  if (tipo == "acu") {
    tipoInf = "Acumulados";
  }
  var hoy = new Date();
  var fechaHoy =
    hoy.getFullYear() + "-" + (hoy.getMonth() + 1) + "-" + hoy.getDate();
  var nombre_informe = "informe " + tipoInf + " " + fechaHoy;
  var datosExp = [];
  var filas = document.querySelectorAll("table tr");
  for (var i = 0; i < filas.length; i++) {
    var fila = [];
    var colus = filas[i].querySelectorAll("td, th");
    for (var j = 0; j < colus.length; j++) {
      fila.push(colus[j].innerText);
    }
    datosExp.push(fila.join(";"));
  }
  // console.log(datosExp.join("\n"));
  descargarArchivoCSV(datosExp.join("\n"), nombre_informe);
}
//descarga el archivo CSV
function descargarArchivoCSV(csv, archivo) {
  var archivo_csv, link_descarga;
  archivo_csv = new Blob([csv], { type: "text/csv" });
  link_descarga = document.createElement("a");
  link_descarga.setAttribute("target", "_blank");
  link_descarga.setAttribute(
    "href",
    "data:text/csv;charset=utf-8," + encodeURIComponent(archivo_csv)
  );
  link_descarga.download = archivo;
  link_descarga.href = window.URL.createObjectURL(archivo_csv);
  link_descarga.style.display = "none";
  document.body.appendChild(link_descarga);
  link_descarga.click();
}
//crea objeto js-html2pdf
//docs de la librería: https://openbase.com/js/js-html2pdf/documentation#dependencies
function pasarAPDF() {
  //https://openbase.com/js/js-html2pdf/documentation
  var hoy = new Date();
  var fechaHoy =
    hoy.getFullYear() + "-" + (hoy.getMonth() + 1) + "-" + hoy.getDate();
  var nombre_informe = "informe " + fechaHoy + ".pdf";
  var informe = document.getElementById("espacioInforme");
  var opt = {
    margin: [10, 0, 10, 0],
    filename: nombre_informe,
    image: { type: "jpeg", quality: 0.98 },
    html2canvas: { scale: 2, logging: true, dpi: 300, letterRendering: true },
    jsPDF: { unit: "mm", format: "a4", orientation: "portrait" },
  };
  var exp_informe = new html2pdf(informe, opt);
  exp_informe.getPdf(true).then((pdf) => {});
}
//elimina los ajustes
function reset() {
  document.getElementById("espacioInforme").innerHTML = "";
}
//abre o cierra la cabeza de opciones para el informe
function opciones() {
  if (screen.width < 900) {
    if (screen.width < 600) {
      if (document.getElementById("informesNorte").style.height == "30%") {
        document.getElementById("informesNorte").style.height = 0;
        document.getElementById("btnMenuInformes").style.top = "4%";
        document.getElementById("informesSur").style.height = "130%";
      } else {
        document.getElementById("informesNorte").style.height = "30%";
        document.getElementById("btnMenuInformes").style.top = "27.5%";
        document.getElementById("informesSur").style.height = "100%";
      }
    } else {
      if (document.getElementById("informesNorte").style.height == "15%") {
        document.getElementById("informesNorte").style.height = 0;
        document.getElementById("btnMenuInformes").style.top = "6%";
        document.getElementById("informesSur").style.height = "100%";
      } else {
        document.getElementById("informesNorte").style.height = "15%";
        document.getElementById("btnMenuInformes").style.top = "19.5%";
        document.getElementById("informesSur").style.height = "100%";
      }
    }
  } else {
    if (document.getElementById("informesNorte").style.height == "20%") {
      document.getElementById("informesNorte").style.height = 0;
      document.getElementById("btnMenuInformes").style.top = "6%";
      document.getElementById("informesSur").style.height = "120%";
    } else {
      document.getElementById("informesNorte").style.height = "20%";
      document.getElementById("informesSur").style.height = "100%";
      document.getElementById("btnMenuInformes").style.top = "22%";
    }
  }
}
//inicia con valores los formularios de las fechas
function inicioFin() {
  Date.prototype.seteardesde = function () {
    var manana = new Date(new Date().getTime() + 24 * 60 * 60 * 1000);
    return manana.toJSON().slice(0, 10);
  };
  Date.prototype.setearHasta = function () {
    var semant = new Date(new Date().getTime() - 7 * 24 * 60 * 60 * 1000);
    return semant.toJSON().slice(0, 10);
  };
  $(document).ready(function () {
    $("#fechaInicio").val(new Date().seteardesde());
  });
  $(document).ready(function () {
    $("#fechaFin").val(new Date().setearHasta());
  });
}
//crea un informe basándose en los ajustes y preferencias elegidas
function obtenerInforme() {
  tipo = document.querySelector('input[name="radInforme"]:checked').value;
  var opcion = "";
  var tipoInf = "";
  if (tipo == "cau") {
    opcion = "cau";
    tipoInf = "caudales";
  }
  if (tipo == "niv") {
    opcion = "niv";
    tipoInf = "niveles";
  }
  if (tipo == "acu") {
    opcion = "acu";
    tipoInf = "acumulados";
  }
  if (tipo == "clo") {
    opcion = "clo";
    tipoInf = "cloros y turbidez";
  }
  var fInicio = document.getElementById("fechaInicio").value;
  var fFin = document.getElementById("fechaFin").value;
  var nestaciones = Array();
  var estaciones = $("#opcionesEstacion").val();
  var nestaciones = [];
  for (var est in estaciones) {
    nestaciones[estaciones[est]] = document
      .getElementById("est" + estaciones[est])
      .getAttribute("name");
  }
  var arrEstaciones = JSON.stringify(estaciones);
  var arrNombres = JSON.stringify(nestaciones);
  $(document).ready(function () {
    $.ajax({
      type: "POST",
      data: {
        opcion: opcion,
        fechaIni: fInicio,
        fechaFin: fFin,
        arrEstaciones: arrEstaciones,
        arrNombres: arrNombres,
      },

      url: "/Aquando.com/A_Informes.php",
      success: function (informe) {
        reset();
        var ahora = new Date();
        var fechahora =
          "" +
          ahora.getDate() +
          "-" +
          (ahora.getMonth() + 1) +
          "-" +
          ahora.getFullYear() +
          " a las " +
          ahora.getHours() +
          ":" +
          ahora.getMinutes();
        var cabecera =
          "<h1 style='color:rgb(1, 168, 184);'>Informe sobre " +
          tipoInf +
          "</h1><hr><p style='color:rgb(65, 65, 65);'>Desde: " +
          fInicio +
          " hasta: " +
          fFin +
          " </p><p style='color:rgb(65, 65, 65);'>Por " +
          nomusuario +
          " el " +
          fechahora +
          "</p><br>";
        var pie =
          '<p style="text-align:center">powered by <img src="../../logo.png" style="height: 3.5em; margin-left: 1%;"></p>';
        document.getElementById("espacioInforme").innerHTML += cabecera;
        document.getElementById("espacioInforme").innerHTML += informe;
        document.getElementById("espacioInforme").innerHTML += pie;
      },
      error: function () {
        console.log("error en los informes");
      },
    });
  });
}
