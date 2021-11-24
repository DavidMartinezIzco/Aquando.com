        function fechaYHora() {
            var currentdate = new Date(); 
            var datetime = currentdate.getDate() + "/"
                    + (currentdate.getMonth()+1)  + "/" 
                    + currentdate.getFullYear() + " <br> "  
                    + currentdate.getHours() + ":"  
                    + currentdate.getMinutes() + ":" 
                    + currentdate.getSeconds();
            document.getElementById('fechahora').innerHTML = datetime;
        }


        var tiempoMax = 10;  // 10 minutes of inactivity
        var tiempoStandBy = 0;
        document.onclick = function() {
            tiempoStandBy = 0;
        };
        document.onmousemove = function() {
            tiempoStandBy = 0;
        };
        
        function comprobarTiempo() {
        if(document.getElementById("seccion").value != "login")
            tiempoStandBy++;
            if (tiempoStandBy == tiempoMax) {
                window.location.href = "/Aquando/public/index.php?log=out";
            }
        }

