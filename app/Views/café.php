<?= $this->extend('inicio') ?>

<?= $this->section('content') ?>
<link rel="stylesheet" type="text/css" href="css/cafe.css">
<main style="padding-left: 3em; color:black">
    <div id="menu">
        <i style="font-size:400%" class="fas fa-coffee"></i>
        <hr>
            <button  onclick="calcular('gabriel')">Gabriel</button>
            <button  onclick="calcular('adrian')">Adrian</button>
            <button  onclick="calcular('david')">David</button>
            <button  onclick="calcular('aida')">Aida</button>
            <button  onclick="calcular('nines')">Nines</button>
            <button  onclick="calcular('jon')">Jon</button>
            <button  onclick="calcular('julen')">Julen</button>
            
        <div id="cafeResul">
            
        </div>
        
    </div>
</main>

<script>


function calcular(persona) {

    var lista = document.getElementById("cafeResul");
    
    var total = document.getElementById("total");
    

    if (persona == "gabriel") {
        lista.innerHTML += "<p>Gabriel: té con leche</p>";
        dinero += 1.30;
    }

    if (persona == "adrian") {
        lista.innerHTML += "<p>Adrian: café con leche</p>";
        dinero += 1.30;
    }

    if (persona == "david") {
        lista.innerHTML += "<p>David: café con leche</p>";
        dinero += 1.30;
    }

    if (persona == "nines") {
        lista.innerHTML += "<p>Nines: café con leche</p>";
        dinero += 1.30;
    }

    if (persona == "aida") {
        lista.innerHTML += "<p>Aida: perfumado</p>";
        dinero += 1.30;
    }

    if (persona == "jon") {
        lista.innerHTML += "<p>Jon: café solo</p>";
        dinero += 1.30;
    }

    if (persona == "julen") {
        lista.innerHTML += "<p>Julen: café con leche</p>";
        dinero += 1.30;
    }
    
}
</script>


<?= $this->endSection() ?>