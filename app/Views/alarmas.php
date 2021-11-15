<?= $this->extend('inicio') ?>
<?= $this->section('content') ?>
<script src='css/echarts.min.js'></script>
<link rel="stylesheet" type="text/css" href="css/alarmas.css">
<main id="conPrincipal" style="margin-left:2.5%; height: 53em; background-color: rgb(56, 56, 56); width:100%; border-radius:10px; margin-top:1%; padding: 0.5%">

<div id="zonaOpciones">

    <div id="filtros">
        <input type="checkbox">filtro</input>
        <input type="checkbox">filtro</input>
        <input type="checkbox">filtro</input>
        <input type="checkbox">filtro</input>
    </div>

    <div id="filtros2">
        <input type="checkbox">opcion</input>
        <input type="checkbox">opcion</input>
        <input type="checkbox">opcion</input>
        <input type="checkbox">opcion</input>
    </div>

    <div id="acciones">
        <button id="btnControl" style="background-color: yellowgreen;" value="aplicar" name="btnControl">aplicar</button>
        <button id="btnControl" type="reset" onclick=limpiar() style="background-color: tomato;" value="reset" name="btnControlReset">reset</button>
        <button id="btnControl" style="background-color: darkseagreen;" value="print" onclick="imprimir()" name="btnControlPrint"><i class="fas fa-print"></i></button>
    </div>

    
</div>

<div id="zonaAlarmas">
    <table id="tablaAlarmas">
    <tr>
                <th></th>
                <th>Fecha y Hora</th>
                <th> Origen</th>
                <th>Estado</th>
                <th>Etiqueta</th>
            </tr>
    </table>
</div>


</main>

<script>

    window.onload = function () {
        alarmas();
    }

    function alarmas() {
        var cont = 0;
        while (cont <= 30) {
            document.getElementById("tablaAlarmas").innerHTML += '<tr><td><input type="checkbox" disabled></td><td>patata</td><td>patata</td><td>patata</td><td>patata</td></tr>';
            cont ++;
        }
        document.getElementById("tablaAlarmas").style.overflowY = 'scroll';
    }
</script>

<?= $this->endSection() ?>