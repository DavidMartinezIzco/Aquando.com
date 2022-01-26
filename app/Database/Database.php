<?php
//algun dia documentaré esta clase entera
// o igual no

class Database
{
    // ¿habra que cambiar esto algun dia?
    // puede que si, puede que no
    
    private $host = "172.16.3.2";
    private $dbname = "Aquando";
    private $user = "postgres";
    private $password = "123456";
    private $conexion;


    public function __construct(){
    }


    //conecta con la BD
    //uso interno
    private function conectar(){
        return $this->conexion = pg_connect("host=$this->host dbname=$this->dbname user=$this->user password=$this->password");
    }

    //obtiene el ID de un usuario dadas sus credenciales en caso de que exista
    //apaño para algunas secciones
    public function obtenerIdUsuario($nombre, $pwd, $id_cliente){
        if ($this->conectar()) {
            $consulta = "SELECT id_usuario FROM usuarios WHERE nombre ='$nombre' AND password ='$pwd' AND id_cliente = " . $id_cliente . "";
            $resultado = pg_query($this->conexion, $consulta);
            if ($this->consultaExitosa($resultado)) {
                $id_usu = pg_fetch_all($resultado);
                return $id_usu;
            } else {
                echo "<script>alert('error de conexion')</script>";
                return false;
            }
        }
    }

    //comprueba si una consulta a BD tiene respuesta
    //uso interno
    private function consultaExitosa($resultado){
        $nResuls = pg_num_rows($resultado);
        if ($nResuls != 0 || $nResuls != null) {
            return true;
        } else {
            return false;
        }
    }

    //comprueba que un usuario exite en la BD
    //está pendiente de cambios (encriptación)
    //se usa en el login
    public function existeUsuario($nombre, $pwd, $id_cliente){
        if ($this->conectar()) {
            $consulta = "SELECT * FROM public.usuarios WHERE nombre ='$nombre' AND password ='$pwd' AND id_cliente = '$id_cliente'";
            $resultado = pg_query($this->conexion, $consulta);
            if ($this->consultaExitosa($resultado)) {
                return true;
            } else {
                return false;
            }
        }
    }
    //obtiene las estacioenes que pertenecen a un usuario
    //se usa en varios sitios
    public function mostrarEstacionesCliente($nombre, $pwd){
        if ($this->conectar()) {
            $consulta = "SELECT estaciones.nombre_estacion, estaciones.id_estacion FROM usuarios INNER JOIN usuario_estacion ON usuarios.id_usuario = usuario_estacion.id_usuario INNER JOIN estaciones ON usuario_estacion.id_estacion = estaciones.id_estacion WHERE usuarios.nombre ='$nombre' AND usuarios.password ='$pwd'";
            $resultado = pg_query($this->conexion, $consulta);
            if ($this->consultaExitosa($resultado)) {
                $estacionesArr = pg_fetch_all($resultado);
                return $estacionesArr;
            } else {
                return false;
            }
        }
    }

    //obtiene las alarmas en general de un usuario
    //se usa en varias cosas
    public function obtenerAlarmasUsuario($id_usuario, $orden, $sentido){
        if ($this->conectar()) {

            $prioridad = 'alarmas.fecha_origen';
            if ($orden != null) {
                $prioridad = 'alarmas.fecha_origen';
                if ($orden != null) {
                    $prioridad = null;
                    switch ($orden) {
                        case 'estado':
                            $prioridad = 'alarmas.estado';
                            break;
                        case 'senal':
                            $prioridad = 'tags.nombre_tag';
                            break;
                        case 'restauracionfecha':
                            $prioridad = 'alarmas.fecha_restauracion';
                            break;
                        case 'estacion':
                            $prioridad = 'estaciones.nombre_estacion';
                            break;
                        case 'reconfecha':
                            $prioridad = 'alarmas.fecha_ack';
                            break;
                        case 'reconusu':
                            $prioridad = 'alarmas.ack_por';
                            break;
                        case 'valor':
                            $prioridad = 'alarmas.valor_alarma';
                            break;
                        case 'origenfecha':
                            $prioridad = 'alarmas.fecha_origen';
                            break;

                        default:
                            $prioridad = 'alarmas.fecha_origen';
                            break;
                    }
                }
            }

            $conAlarmas = "SELECT 
                estaciones.nombre_estacion, tags.nombre_tag, alarmas.id_alarmas, alarmas.valor_alarma, alarmas.fecha_origen, alarmas.fecha_restauracion, alarmas.estado, alarmas.ack_por, alarmas.fecha_ack 
                FROM alarmas INNER JOIN estacion_tag ON alarmas.id_tag = estacion_tag.id_tag INNER JOIN usuario_estacion ON usuario_estacion.id_estacion = estacion_tag.id_estacion INNER JOIN estaciones ON estaciones.id_estacion = estacion_tag.id_estacion INNER JOIN tags ON alarmas.id_tag = tags.id_tag
                WHERE usuario_estacion.id_usuario = " . $id_usuario[0]['id_usuario'] . " ORDER BY $prioridad DESC";

            if ($sentido != null) {
                if ($sentido == 'ASC') {
                    $conAlarmas = "SELECT 
                    estaciones.nombre_estacion, tags.nombre_tag, alarmas.id_alarmas, alarmas.valor_alarma, alarmas.fecha_origen, alarmas.fecha_restauracion, alarmas.estado, alarmas.ack_por, alarmas.fecha_ack 
                    FROM alarmas INNER JOIN estacion_tag ON alarmas.id_tag = estacion_tag.id_tag INNER JOIN usuario_estacion ON usuario_estacion.id_estacion = estacion_tag.id_estacion INNER JOIN estaciones ON estaciones.id_estacion = estacion_tag.id_estacion INNER JOIN tags ON alarmas.id_tag = tags.id_tag
                    WHERE usuario_estacion.id_usuario = " . $id_usuario[0]['id_usuario'] . " ORDER BY $prioridad ASC";
                }
            }

            $resulAlarmas = pg_query($conAlarmas);
            if ($this->consultaExitosa($resulAlarmas)) {
                $alarmas = pg_fetch_all($resulAlarmas);
                return $alarmas;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    //obtiene las alarmas de una estación en concreto
    //se usa para varias cosas
    public function obtenerAlarmasEstacion($id_estacion, $orden, $sentido, $fechaInicio, $fechaFin){
        if ($fechaInicio != null) {
            //traducir fecha
        }
        if ($fechaFin != null) {
            //traducir fecha
        }

        $prioridad = 'alarmas.fecha_origen';
        if ($orden != null) {
            $prioridad = null;
            switch ($orden) {
                case 'estado':
                    $prioridad = 'alarmas.estado';
                    break;
                case 'senal':
                    $prioridad = 'tags.nombre_tag';
                    break;
                case 'restauracionfecha':
                    $prioridad = 'alarmas.fecha_restauracion';
                    break;
                case 'estacion':
                    $prioridad = 'estaciones.nombre_estacion';
                    break;
                case 'reconfecha':
                    $prioridad = 'alarmas.fecha_ack';
                    break;
                case 'reconusu':
                    $prioridad = 'alarmas.ack_por';
                    break;
                case 'valor':
                    $prioridad = 'alarmas.valor_alarma';
                    break;
                case 'origenfecha':
                    $prioridad = 'alarmas.fecha_origen';
                    break;

                default:
                    $prioridad = 'alarmas.fecha_origen';
                    break;
            }
        }

        if ($this->conectar()) {

            $consulta = "SELECT estaciones.nombre_estacion, tags.nombre_tag, alarmas.id_alarmas, alarmas.valor_alarma, alarmas.fecha_origen, alarmas.fecha_restauracion, alarmas.estado, alarmas.ack_por, alarmas.fecha_ack 
            FROM alarmas INNER JOIN estacion_tag ON alarmas.id_tag = estacion_tag.id_tag INNER JOIN usuario_estacion ON usuario_estacion.id_estacion = estacion_tag.id_estacion INNER JOIN estaciones ON estaciones.id_estacion = estacion_tag.id_estacion INNER JOIN tags ON alarmas.id_tag = tags.id_tag
            WHERE estacion_tag.id_estacion = '$id_estacion' ORDER BY $prioridad DESC";
            if ($sentido != null) {
                if ($sentido == 'ASC') {
                    $consulta = "SELECT estaciones.nombre_estacion, tags.nombre_tag, alarmas.id_alarmas, alarmas.valor_alarma, alarmas.fecha_origen, alarmas.fecha_restauracion, alarmas.estado, alarmas.ack_por, alarmas.fecha_ack 
                    FROM alarmas INNER JOIN estacion_tag ON alarmas.id_tag = estacion_tag.id_tag INNER JOIN usuario_estacion ON usuario_estacion.id_estacion = estacion_tag.id_estacion INNER JOIN estaciones ON estaciones.id_estacion = estacion_tag.id_estacion INNER JOIN tags ON alarmas.id_tag = tags.id_tag
                    WHERE estacion_tag.id_estacion = '$id_estacion' ORDER BY $prioridad ASC";
                }
            }

            $resultado = pg_query($this->conexion, $consulta);


            if ($this->consultaExitosa($resultado)) {
                $alarmasEstacion = pg_fetch_all($resultado);
                return $alarmasEstacion;
            }
        } else {
            return false;
        }
    }

    //obtiene la ultima información conocida de una estación concreta
    //se usará en la sección de estaciones y probablemente mediante AJAX
    public function datosEstacion($id_estacion, $todos){
        //SACAR TAGS DE ESTACION
        //DE CADA TAG SACAR LA ULTIMA FECHA Y SU VALOR

        if ($this->conectar()) {
            $ultimosDatosEstacion = array();
            if($todos){
                $tagsEstacion = $this->todosTagsEstacion($id_estacion);
            }
            else {
                $tagsEstacion = $this->tagsEstacion($id_estacion);
            }
            
            foreach ($tagsEstacion as $index => $tag) {
                $conFechaMaxTag = "SELECT MAX(datos_valores.fecha) FROM datos_valores INNER JOIN estacion_tag on datos_valores.id_tag = estacion_tag.id_tag  WHERE datos_valores.id_tag = ".$tag['id_tag']." AND estacion_tag.id_estacion = ".$id_estacion."";
                $conUltimoValorTag = "SELECT tags.nombre_tag,
                datos_valores.*
                FROM datos_valores INNER JOIN tags ON datos_valores.id_tag = tags.id_tag
                INNER JOIN estacion_tag ON estacion_tag.id_tag = tags.id_tag
                WHERE tags.id_tag = ".$tag['id_tag']."AND datos_valores.fecha = ($conFechaMaxTag) AND estacion_tag.id_estacion = ".$id_estacion."";

                $resulConUltimoValorTag = pg_query($this->conexion, $conUltimoValorTag);
                if($this->consultaExitosa($resulConUltimoValorTag)){
                    $ultimosDatosEstacion[$tag['id_tag']] = pg_fetch_all($resulConUltimoValorTag)[0];
                }

            }
            $ultimosDatosEstacionLimpio = array();
            foreach ($ultimosDatosEstacion as $tag => $datosTag) {
                foreach ($datosTag as $nDato => $valor) {
                    if($nDato != 'nombre_tag' && $nDato != 'id_tag' && $nDato!= 'id_datos' && $nDato != 'fecha' && $nDato != 'calidad'){
                        if($valor != null){
                            $ultimosDatosEstacionLimpio[$tag]['valor'] = $valor;
                        }
                    }
                    else{
                        $ultimosDatosEstacionLimpio[$tag][$nDato] = $valor;
                    }
                }
            }

            return $ultimosDatosEstacionLimpio;
        }
    }

    //obtiene los tags historizables de una estacion concreta
    //se usa en graficas
    public function tagsEstacion($id_estacion){
        if ($this->conectar()) {
            $conTags = "SELECT tags.id_tag, tags.nombre_tag FROM estacion_tag INNER JOIN tags ON tags.id_tag = estacion_tag.id_tag WHERE estacion_tag.id_estacion = $id_estacion AND tags.historizar = true";
            $resulTags = pg_query($this->conexion, $conTags);
            if ($this->consultaExitosa($resulTags)) {
                $tagsEstacion = pg_fetch_all($resulTags);

                $_SESSION['tagsEstacion'] = $tagsEstacion;
                return $tagsEstacion;
            } else {
                return false;
            }
            return false;
        }
    }

    public function todosTagsEstacion($id_estacion){
        if ($this->conectar()) {
            $conTags = "SELECT tags.id_tag, tags.nombre_tag FROM estacion_tag INNER JOIN tags ON tags.id_tag = estacion_tag.id_tag WHERE estacion_tag.id_estacion = $id_estacion";
            $resulTags = pg_query($this->conexion, $conTags);
            if ($this->consultaExitosa($resulTags)) {
                $tagsEstacion = pg_fetch_all($resulTags);

                $_SESSION['tagsEstacion'] = $tagsEstacion;
                return $tagsEstacion;
            } else {
                return false;
            }
            return false;
        }
    }

    //para las fechas vamos a necesitar un traductor de Date() a TimeStamp()
    public function historicosEstacion($id_estacion, $fechaIni, $fechaFin){
        if ($this->conectar()) {

            $tagsEstacion = $this->tagsEstacion($id_estacion);
            $_SESSION['tagsEstacion'] = $tagsEstacion;
            if ($tagsEstacion != false) {

                foreach ($tagsEstacion as $index => $tag) {
                    if ($tag['id_tag'] != 1) {
                        $conHisto = "SELECT fecha, calidad, valor_bool, valor_int, valor_acu, valor_float, valor_string, valor_date FROM datos_historicos WHERE id_tag = " . $tag['id_tag'] . " ";
                        if ($fechaIni != "") {
                            $conHisto .= " AND fecha >= $fechaIni ";
                        }
                        if ($fechaFin != "") {
                            $conHisto .=  " AND fecha <= $fechaFin ";
                        }
                        $conHisto .= " ORDER BY fecha DESC LIMIT 100";

                        $resulHisto = pg_query($this->conexion, $conHisto);
                        if ($this->consultaExitosa($resulHisto)) {
                            $historicoTag = pg_fetch_all($resulHisto);
                            $historicos[$tag['nombre_tag']] = $historicoTag;
                        }
                    }
                }
                return $historicos;
            }
        } else {
            return false;
        }
    }

    //obtiene los historicos de un tag de una estación
    //se usa en graficas->vista rápida
    public function historicosTagEstacion($id_estacion, $id_tag){
        if ($this->conectar()) {
            $conHistoTagEst = "SELECT datos_historicos.fecha, datos_historicos.calidad, datos_historicos.valor_bool, datos_historicos.valor_int, datos_historicos.valor_acu, datos_historicos.valor_float, datos_historicos.valor_string, datos_historicos.valor_date FROM datos_historicos INNER JOIN estacion_tag ON estacion_tag.id_tag = datos_historicos.id_tag WHERE estacion_tag.id_tag = " . $id_tag . " AND estacion_tag.id_estacion = ".$id_estacion." ORDER BY datos_historicos.fecha DESC";
            $resulHistoTagEst = pg_query($this->conexion, $conHistoTagEst);
            if ($this->consultaExitosa($resulHistoTagEst)) {
                $datosHistoTagEst = pg_fetch_all($resulHistoTagEst);
                $datosHisto = array();
                foreach ($datosHistoTagEst as $index => $dato) {
                    foreach ($dato as $factor => $valor) {
                        if ($valor != null) {
                            $datosHisto[$index][$factor] = $valor;
                        }
                    }
                }
                return $datosHisto;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    //funcion para AJAX, obtiene los historicos de un tag de una estación en un periodo determinado
    //se usa en graficas-> vista personalizada
    public function historicosTagEstacionCustom($id_estacion, $id_tag, $ajustesMeta, $fechaInicio, $fechaFin){

        if ($this->conectar()) {

            $seriesTagCustom = array();
            $metaCustom = array();

            //obtener el metadata del TAG 
            $meta = $this->metaTag($id_tag, $id_estacion);

            // filtrar metadata
            foreach ($ajustesMeta as $index => $tipo) {
                if($tipo == "maxGen"){
                    $metaCustom['max'] = $meta['max'];
                }
                if($tipo == "minGen"){
                    $metaCustom['min'] = $meta['min'];
                }
                if($tipo == "avgGen"){
                    $metaCustom['avg'] = $meta['avg'];
                }
            }

            $seriesTagCustom['meta'] = $metaCustom;


            //traducir fechas(?)

            $ini = strtotime($fechaInicio);
            $fin = strtotime($fechaFin);

            

            //obtener "Series" del TAG delimitado por las fechas (supongo) (falta el WHERE)
            $conHistoTagEst = "SELECT datos_historicos.fecha, datos_historicos.calidad, datos_historicos.valor_bool, datos_historicos.valor_int, datos_historicos.valor_acu, datos_historicos.valor_float, datos_historicos.valor_string, datos_historicos.valor_date 
            FROM datos_historicos INNER JOIN estacion_tag ON estacion_tag.id_tag = datos_historicos.id_tag 
            WHERE  estacion_tag.id_tag = " . $id_tag . " AND estacion_tag.id_estacion = ".$id_estacion." AND cast(extract(epoch from datos_historicos.fecha) as integer) < ".$ini." AND cast(extract(epoch from datos_historicos.fecha) as integer) > ".$fin." 
            ORDER BY datos_historicos.fecha DESC";
            $resulHistoTagEst = pg_query($this->conexion, $conHistoTagEst);
            if ($this->consultaExitosa($resulHistoTagEst)) {
                $datosHistoTagEst = pg_fetch_all($resulHistoTagEst);
                $datosHisto = array();
                foreach ($datosHistoTagEst as $index => $dato) {
                    foreach ($dato as $factor => $valor) {
                        if ($valor != null) {
                            $datosHisto[$index]['valor'] = $valor;
                        }
                        if($factor == 'fecha'){
                            $datosHisto[$index]['fecha'] = $valor;
                        }
                    }
                }

                //devolver array unico con las "series" y el "meta" del tag
                $seriesTagCustom['tag']= $datosHisto;
                
            } else {
                return false;
            }
        } else {
            return false;
        }

        return $seriesTagCustom;
    }

    //secuencia para pasar una alarma de un usuario a estado ACK e incluir nombre del usuario y la fecha de ACK
    //se usa en la sección de alarmas

    public function reconocerAlarma($id_alarma, $usuario, $hora){

        if ($this->conectar()) {
            $conDatosAlarma = "SELECT estado FROM alarmas WHERE id_alarmas = $id_alarma";
            $resulDatosAlarma = pg_query($this->conexion, $conDatosAlarma);
            if ($this->consultaExitosa($resulDatosAlarma)) {
                $datosAlarma = pg_fetch_all($resulDatosAlarma);
                $estadoAlarma = $datosAlarma[0]['estado'];

                if ($estadoAlarma == "1") {
                    $nuevoEstado = 3;
                }
                if ($estadoAlarma == "2") {
                    $nuevoEstado = 4;
                }

                $secuencia = "UPDATE alarmas SET estado = $nuevoEstado, ack_por = '$usuario', fecha_ack = '$hora' WHERE id_alarmas = $id_alarma";
                $resultado = pg_query($this->conexion, $secuencia);
                if ($this->consultaExitosa($resultado)) {
                    return true;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    //proceso para AJAX para obetener las ultimas alarmas de un usuario y mostrarlas en el menu SUR
    //se usa en casi todas partes y en todo momento
    public function alarmasSur($id_usuario){
        if ($this->conectar()) {
            $conAlarmas = "SELECT estaciones.nombre_estacion, tags.nombre_tag, alarmas.id_alarmas, alarmas.valor_alarma, alarmas.fecha_origen, alarmas.fecha_restauracion, alarmas.estado, alarmas.ack_por, alarmas.fecha_ack 
            FROM alarmas INNER JOIN estacion_tag ON alarmas.id_tag = estacion_tag.id_tag INNER JOIN usuario_estacion ON usuario_estacion.id_estacion = estacion_tag.id_estacion INNER JOIN estaciones ON estaciones.id_estacion = estacion_tag.id_estacion INNER JOIN tags ON alarmas.id_tag = tags.id_tag
            WHERE usuario_estacion.id_usuario = " . $id_usuario[0]['id_usuario'] . " ORDER BY alarmas.fecha_origen DESC limit 7";

            $resulAlarmas = pg_query($conAlarmas);
            if ($this->consultaExitosa($resulAlarmas)) {
                $alarmas = pg_fetch_all($resulAlarmas);
                return $alarmas;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    //proceso para AJAX para obtener las alarmas del Menu SUR
    //se usa en las secciones de estacion
    public function alarmasEstacionSur($id_estacion){
        if ($this->conectar()) {
            $consulta = "SELECT estaciones.nombre_estacion, tags.nombre_tag, alarmas.id_alarmas, alarmas.valor_alarma, alarmas.fecha_origen, alarmas.fecha_restauracion, alarmas.estado, alarmas.ack_por, alarmas.fecha_ack 
            FROM alarmas INNER JOIN estacion_tag ON alarmas.id_tag = estacion_tag.id_tag INNER JOIN usuario_estacion ON usuario_estacion.id_estacion = estacion_tag.id_estacion INNER JOIN estaciones ON estaciones.id_estacion = estacion_tag.id_estacion INNER JOIN tags ON alarmas.id_tag = tags.id_tag
            WHERE estacion_tag.id_estacion = '$id_estacion' ORDER BY alarmas.fecha_origen DESC LIMIT 7";
            $resultado = pg_query($this->conexion, $consulta);
            if ($this->consultaExitosa($resultado)) {
                $alarmasEstacion = pg_fetch_all($resultado);
                return $alarmasEstacion;
            }
        } else {
            return false;
        }
    }

    //secuencia para sacar las ultimas conexiones de las estaciones
    //se usa en comunicaciones
    public function ultimaComunicacionEstacion($id_estacion){
        if ($this->conectar()) {
            $consulta = "SELECT estaciones.id_estacion,estaciones.nombre_estacion, datos_valores.valor_date, tags.nombre_tag FROM estaciones INNER JOIN estacion_tag ON estaciones.id_estacion = estacion_tag.id_estacion INNER JOIN tags ON tags.id_tag = estacion_tag.id_tag INNER JOIN datos_valores ON estacion_tag.id_tag = datos_valores.id_tag WHERE tags.nombre_tag LIKE 'Ultima Comunicacion%' AND estaciones.id_estacion = " . $id_estacion . " ORDER BY estaciones.nombre_estacion DESC";
            $resultado = pg_query($this->conexion, $consulta);
            if ($this->consultaExitosa($resultado)) {
                $ultimaConexion = pg_fetch_all($resultado);
                return $ultimaConexion;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    //obtiene la calidad de los ultimos datos de una estacion
    //se usa en comunicaciones
    public function calidadTagsEstacion($id_estacion){
        if ($this->conectar()) {
            $conCalidad = "SELECT datos_valores.calidad, tags.nombre_tag FROM estaciones INNER JOIN estacion_tag ON estaciones.id_estacion = estacion_tag.id_estacion INNER JOIN tags ON tags.id_tag = estacion_tag.id_tag INNER JOIN datos_valores ON estacion_tag.id_tag = datos_valores.id_tag WHERE estaciones.id_estacion = " . $id_estacion . " ORDER BY tags.nombre_tag DESC";
            $resCalidad = pg_query($this->conexion, $conCalidad);
            if ($this->consultaExitosa($resCalidad)) {
                $calidadTags = pg_fetch_all($resCalidad);
                return $calidadTags;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    //adivina que hace
    //se usa en varios sitios
    public function obtenerNombreEstacion($id_estacion){
        if ($this->conectar()) {
            $consulta = "SELECT nombre_estacion FROM estaciones WHERE id_estacion = " . $id_estacion;
            $resul = pg_query($consulta);
            if ($this->consultaExitosa($resul)) {
                $estacion = pg_fetch_all($resul);
                return $estacion;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    //obtiene los metadatos de un tag de una estacion
    //se usa en las graficas
    public function metaTag($id_tag, $id_estacion){
        //si tiene consignas pues las consignas(igual en un futuro)
        if($this->conectar()){
            $metaDatos = array();
            $conmaxval = "SELECT MAX(datos_historicos.valor_int), CAST(MAX(datos_historicos.valor_float)*100 AS INT), MAX(datos_historicos.valor_acu) FROM datos_historicos INNER JOIN estacion_tag ON estacion_tag.id_tag = datos_historicos.id_tag WHERE datos_historicos.id_tag = ".$id_tag." AND estacion_tag.id_estacion = ".$id_estacion."";
            $conminval = "SELECT MIN(datos_historicos.valor_int),CAST(MIN(datos_historicos.valor_float)*100 AS INT), MIN(datos_historicos.valor_acu) FROM datos_historicos INNER JOIN estacion_tag ON estacion_tag.id_tag = datos_historicos.id_tag WHERE datos_historicos.id_tag = ".$id_tag." AND estacion_tag.id_estacion = ".$id_estacion."";
            $conmedia = "SELECT AVG(datos_historicos.valor_int),CAST(AVG(datos_historicos.valor_float)*100 AS INT), AVG(datos_historicos.valor_acu) FROM datos_historicos INNER JOIN estacion_tag ON estacion_tag.id_tag = datos_historicos.id_tag WHERE datos_historicos.id_tag = ".$id_tag." AND estacion_tag.id_estacion = ".$id_estacion."";

            $resulmaxval = pg_query($this->conexion,$conmaxval);
            $resulminval = pg_query($this->conexion,$conminval);
            $resulmedia = pg_query($this->conexion,$conmedia);

            if($this->consultaExitosa($resulmaxval)){
                $maxval = pg_fetch_all($resulmaxval);
                foreach ($maxval[0] as $index => $valor) {
                    if($valor != null){
                        if($index = 'int4'){
                            $metaDatos['max'] = $valor/100;
                        }
                        else {
                            $metaDatos['max'] = $valor;
                        }
                        
                    }
                }
            }
            if($this->consultaExitosa($resulminval)){
                $minval = pg_fetch_all($resulminval);
                foreach ($minval[0] as $index => $valor) {
                    if($valor != null){
                        if($index = 'int4'){
                            $metaDatos['min'] = $valor/100;
                        }
                        else {
                            $metaDatos['min'] = $valor;
                        }
                        
                    }
                }
            }
            if($this->consultaExitosa($resulmedia)){
                $media = pg_fetch_all($resulmedia);
                foreach ($media[0] as $index => $valor) {
                    if($valor != null){
                        if($index = 'int4'){
                            $metaDatos['avg'] = $valor/100;
                        }
                        else {
                            $metaDatos['avg'] = $valor;
                        }
                        
                    }
                }
            }
            return $metaDatos;
        }
        else {
            return false;
        }

    }

    //obtiene los 7 ultimos maximos valores de un tag de una estación
    public function tagTrend($id_tag, $id_estacion){

        if($this->conectar()){


            $conTrend = 
            "SELECT MAX(datos_historicos.valor_acu) as acu ,MAX(datos_historicos.valor_int) as int ,MAX(datos_historicos.valor_float) as float, datos_historicos.fecha::date
            from datos_historicos inner join estacion_tag on datos_historicos.id_tag = estacion_tag.id_tag
            where datos_historicos.id_tag = ".$id_tag." and estacion_tag.id_estacion = ".$id_estacion."
            and datos_historicos.fecha::date > current_date::date - interval '44 days' GROUP BY datos_historicos.fecha::date";
            $resTrend = pg_query($this->conexion, $conTrend);
            if($this->consultaExitosa(($resTrend))){
                $datosTrendTag = pg_fetch_all($resTrend);
                return($datosTrendTag);
            }
            
        }

    }


}
