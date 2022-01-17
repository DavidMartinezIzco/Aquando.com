function actualizarSur(entorno, nombre, pwd, idemp, estacion) {

    if (entorno == "general") {

        $(document).ready(function() {
            $.ajax({
                type: 'GET',
                url: 'A_Sur.php?caso=general&nombre=' + nombre + '&pwd=' + pwd + '&emp=' + idemp,
                success: function(alarmas) {
                    document.getElementById("alarmasSur").innerHTML = alarmas;
                },
                error: function() {
                    console.log("error");
                }

            });
        });
    }
    if (entorno == "estacion") {
        $(document).ready(function() {
            $.ajax({
                type: 'GET',
                url: 'A_Sur.php?caso=estacion&estacion=' + estacion,
                success: function(alarmas) {
                    document.getElementById("alarmasSur").innerHTML = alarmas;
                },
                error: function() {
                    console.log("error");
                }

            });
        });
    }



}