//refresca la lista de alarmas del menu sur en funcion de la sección en la que se encuentre
function actualizarSur( entorno, nombre, pwd, estacion ) {
    if(screen.width > 800){
        if ( entorno == "general" ) {

            $( document )
                .ready( function () {
                    $.ajax( {
                        type: 'GET',
                        // url: 'http://dateando.ddns.net:3000/Aquando.com/A_Sur.php?caso=general&nombre=' + nombre + '&pwd=' + pwd,
                        url: '/Aquando.com/A_Sur.php?caso=general&nombre=' + nombre + '&pwd=' + pwd,
                        success: function ( alarmas ) {
                            document.getElementById( "alarmasSur" )
                                .innerHTML = alarmas;
                        },
                        error: function () {
                            console.log( "error" );
                        }
                    } );
                } );
        }
        if ( entorno == "estacion" ) {
            $( document )
                .ready( function () {
                    $.ajax( {
                        type: 'GET',
                        // url: 'http://dateando.ddns.net:3000/Aquando.com/A_Sur.php?caso=estacion&estacion=' + estacion,
                        url: '/Aquando.com/A_Sur.php?caso=estacion&estacion=' + estacion,
                        success: function ( alarmas ) {
                            document.getElementById( "alarmasSur" )
                                .innerHTML = alarmas;
                        },
                        error: function () {
                            console.log( "error" );
                        }
    
                    } );
                } );
        }
    
    
    }
    

}