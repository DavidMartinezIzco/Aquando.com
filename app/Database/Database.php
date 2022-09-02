<?php
//algun dia documentaré esta clase entera
// o igual no

class Database
{
    // ¿habra que cambiar esto algun dia?
    // puede que si, puede que no

    private $host = "172.16.5.1";
    private $dbname = "aquando_ddbb";
    private $user = "postgres";
    private $password = "123456";
    private $conexion;

    public function __construct()
    {
        if (!function_exists('str_contains')) {
            function str_contains($pajar, $aguja)
            {
                return $aguja !== '' && mb_stripos($pajar, $aguja) !== false;
            }
        }
    }

    //conecta con la BD
    //uso interno
    private function conectar()
    {
        return $this->conexion = pg_connect("host=$this->host dbname=$this->dbname user=$this->user password=$this->password");
    }

    //comprueba si una consulta a BD tiene respuesta
    //uso interno
    private function consultaExitosa($resultado)
    {
        $nResuls = pg_num_rows($resultado);
        if ($nResuls != 0 || $nResuls != null) {
            return true;
        } else {
            return false;
        }
    }

    //obtiene el ID de un usuario dadas sus credenciales en caso de que exista
    //apaño para algunas secciones
    public function obtenerIdUsuario($nombre, $pwd)
    {
        if ($this->conectar()) {
            $consulta = "SELECT id_usuario FROM usuarios WHERE nombre ='$nombre' AND password ='$pwd'";
            $resultado = pg_query($this->conexion, $consulta);
            if ($this->consultaExitosa($resultado)) {
                $id_usu = pg_fetch_all($resultado);
                return $id_usu;
            } else {
                echo '<script language="javascript">alert("Error de conexión")</script>';
                return false;
            }
        }
    }

    //devuelve el nombre de la empresa de un usuario
    public function obtenerClienteUsuario($nombre_usuario, $pwd)
    {
        $id_usuario = $this->obtenerIdUsuario($nombre_usuario, $pwd)[0]['id_usuario'];
        if ($id_usuario) {
            $con = "SELECT cliente.nombre FROM cliente inner join usuarios on usuarios.id_cliente = cliente.id_cliente
           WHERE usuarios.id_usuario = " . $id_usuario;
            $res = pg_query($this->conexion, $con);
            if ($this->consultaExitosa($res)) {
                return pg_fetch_all($res)[0]['nombre'];
            }
        }
        return "";
    }


    //comprueba que un usuario exite en la BD
    //está pendiente de cambios (encriptación)
    //se usa en el login
    public function existeUsuario($nombre, $pwd)
    {
        if ($this->conectar()) {
            $consulta = "SELECT * FROM public.usuarios WHERE nombre ='$nombre' AND password ='$pwd'";
            $resultado = pg_query($this->conexion, $consulta);
            if ($this->consultaExitosa($resultado)) {
                return true;
            } else {
                return false;
            }
        } else {
            echo '<script language="javascript">alert("Error de conexión")</script>';
            return false;
        }
    }

    //obtiene las estacioenes que pertenecen a un usuario
    //se usa en varios sitios
    public function mostrarEstacionesCliente($nombre, $pwd)
    {
        if ($this->conectar()) {
            $consulta = "SELECT estaciones.nombre_estacion, estaciones.id_estacion, estaciones.latitud, estaciones.longitud 
            FROM usuarios INNER JOIN usuario_estacion ON usuarios.id_usuario = usuario_estacion.id_usuario 
            INNER JOIN estaciones ON usuario_estacion.id_estacion = estaciones.id_estacion 
            WHERE usuarios.nombre ='$nombre' AND usuarios.password ='$pwd'";
            $resultado = pg_query($this->conexion, $consulta);
            if ($this->consultaExitosa($resultado)) {
                $estacionesArr = pg_fetch_all($resultado);
                return $estacionesArr;
            } else {
                return false;
            }
        }
    }

    //obtiene la foto codificada en base64 de una estacion en concreto
    //la devuelve como texto plano
    public function obtenerFotoEstacion($id_estacion)
    {
        if ($this->conectar()) {
            $consulta = "SELECT foto as foto
            FROM estaciones
            WHERE id_estacion = " . $id_estacion;
            $resultado = pg_query($this->conexion, $consulta);
            if ($this->consultaExitosa($resultado)) {
                $foto = pg_fetch_all($resultado)[0]['foto'];
                return $foto;
            } else {
                return false;
            }
        }
    }


    //obtiene las alarmas en general de un usuario
    //se usa en varias cosas
    public function obtenerAlarmasUsuario($id_usuario, $orden, $sentido, $fechaInicio, $fechaFin)
    {
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
            WHERE usuario_estacion.id_usuario = " . $id_usuario[0]['id_usuario'] . "AND alarmas.fecha_origen::date > current_date::date - interval '30 days'";

            //obtener fechas de inicio y fin
            //comprobar cuales están definidas
            //filtrar

            if ($fechaInicio != null) {
                $ini = strtotime($fechaInicio);
                $conAlarmas .= " AND cast(extract(epoch from alarmas.fecha_origen) as integer) <= " . $ini;
            }
            if ($fechaFin != null) {
                $fin = strtotime($fechaFin);
                $conAlarmas .= " AND cast(extract(epoch from alarmas.fecha_origen) as integer) > " . $fin;
            }

            if ($sentido != null) {
                if ($sentido == 'ASC') {
                    $conAlarmas .= " ORDER BY $prioridad ASC";
                } else {
                    $conAlarmas .= " ORDER BY $prioridad DESC";
                }
            } else {
                $conAlarmas .= " ORDER BY $prioridad DESC";
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
    public function obtenerAlarmasEstacion($id_estacion, $orden, $sentido, $fechaInicio, $fechaFin)
    {
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
        WHERE estacion_tag.id_estacion = '$id_estacion'";


            if ($fechaInicio != null) {
                $ini = strtotime($fechaInicio);
                $consulta .= " AND cast(extract(epoch from alarmas.fecha_origen) as integer) <= " . $ini;
            }
            if ($fechaFin != null) {
                $fin = strtotime($fechaFin);
                $consulta .= " AND cast(extract(epoch from alarmas.fecha_origen) as integer) >= " . $fin;
            }

            if ($sentido != null) {
                if ($sentido == 'ASC') {
                    $consulta .= " ORDER BY $prioridad ASC LIMIT 300";
                } else {
                    $consulta .= " ORDER BY $prioridad DESC LIMIT 300";
                }
            } else {
                $consulta .= " ORDER BY $prioridad DESC LIMIT 300";
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
    //obtiene los historicos de 24h de un tag propio de una alarma
    //obtner el nombre de la señal tambien?
    function obtenerDetallesAlarma($id_alarma)
    {
        if ($this->conectar()) {
            $consulta_id = "SELECT id_tag, fecha_origen from alarmas where id_alarmas = " . $id_alarma . " limit 1";
            $respuesta_id = pg_query($this->conexion, $consulta_id);
            if ($this->consultaExitosa($respuesta_id)) {
                $datos_alarma = pg_fetch_all($respuesta_id);
                $consulta_detalles = "SELECT datos_historicos.valor_bool, datos_historicos.valor_float, datos_historicos.valor_acu, datos_historicos.valor_int, datos_historicos.fecha, estaciones.nombre_estacion, tags.nombre_tag FROM datos_historicos INNER JOIN estacion_tag ON datos_historicos.id_tag = estacion_tag.id_tag INNER JOIN estaciones ON estacion_tag.id_estacion = estaciones.id_estacion INNER JOIN tags ON tags.id_tag = datos_historicos.id_tag WHERE datos_historicos.id_tag= " . $datos_alarma[0]['id_tag'] . " AND(datos_historicos.fecha::date - interval '1 days') < '" . $datos_alarma[0]['fecha_origen'] . "' AND (datos_historicos.fecha::date + interval '1 days') > '" . $datos_alarma[0]['fecha_origen'] . "' ORDER BY datos_historicos.fecha DESC";
                $respuesta_detalles = pg_query($this->conexion, $consulta_detalles);
                if ($this->consultaExitosa($respuesta_detalles)) {
                    $detalles = pg_fetch_all($respuesta_detalles);
                    return $detalles;
                }
            }
        }
        return false;
    }

    //obtiene la ultima información conocida de una estación concreta
    //se usará en la sección de estaciones y probablemente mediante AJAX
    public function datosEstacion($id_estacion, $todos)
    {
        if ($this->conectar()) {
            $tagsEstacion = array();
            $ultimosDatosEstacion = array();
            if ($todos) {
                $tagsEstacion = $this->todosTagsEstacion($id_estacion);
            } else {
                $tagsEstacion = $this->tagsEstacion($id_estacion);
            }
            foreach ($tagsEstacion as $index => $tag) {
                $conUltimoValorTag = "SELECT tags.nombre_tag, tags.unidad, tags.r_max, tags.r_min,
            datos_valores.id_tag, datos_valores.fecha, datos_valores.valor_bool, datos_valores.valor_int, datos_valores.valor_float, datos_valores.valor_acu, datos_valores.valor_string, datos_valores.valor_date 
            FROM datos_valores INNER JOIN tags ON datos_valores.id_tag = tags.id_tag
            INNER JOIN estacion_tag ON estacion_tag.id_tag = tags.id_tag
            WHERE tags.id_tag = " . $tag['id_tag'] . " AND estacion_tag.id_estacion = " . $id_estacion . "";

                $resulConUltimoValorTag = pg_query($this->conexion, $conUltimoValorTag);
                if ($this->consultaExitosa($resulConUltimoValorTag)) {
                    $ultimosDatosEstacion[$tag['id_tag']] = pg_fetch_all($resulConUltimoValorTag)[0];
                }
            }
            $ultimosDatosEstacionLimpio = array();
            foreach ($ultimosDatosEstacion as $tag => $datosTag) {
                foreach ($datosTag as $nDato => $valor) {
                    if ($nDato != 'nombre_tag' && $nDato != 'id_tag' && $nDato != 'id_datos' && $nDato != 'fecha' && $nDato != 'calidad' && $nDato != 'unidad' && $nDato != 'r_max' && $nDato != 'r_min') {
                        if ($valor != null) {
                            $ultimosDatosEstacionLimpio[$tag]['valor'] = $valor;
                        }
                    } else {
                        if ($valor != null) {
                            $ultimosDatosEstacionLimpio[$tag][$nDato] = $valor;
                        }
                    }
                }
            }
            return $ultimosDatosEstacionLimpio;
        }
    }

    //obtiene los tags historizables de una estacion concreta
    //se usa en graficas y en la sección estacion
    public function tagsEstacion($id_estacion)
    {
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

    //saca los tags de una estacion del tipo que sean
    //se usa en varios puntos
    public function todosTagsEstacion($id_estacion)
    {
        if ($this->conectar()) {
            $conTags = "SELECT tags.id_tag, tags.nombre_tag FROM estacion_tag INNER JOIN tags ON tags.id_tag = estacion_tag.id_tag WHERE estacion_tag.id_estacion = " . $id_estacion;
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

    //obtiene los tags analogicos historizables de un grupo de estaciones
    public function tagsAnalogHisto($estaciones)
    {
        $tagsAnalogsHisto = array();
        if ($this->conectar()) {

            foreach ($estaciones as $index) {
                $id = $index->id_estacion;
                $conAnalog = "select tags.id_tag,tags.nombre_tag, estaciones.id_estacion, estaciones.nombre_estacion from tags inner join estacion_tag on tags.id_tag = estacion_tag.id_tag inner join estaciones on estaciones.id_estacion = estacion_tag.id_estacion
                where tags.type_tag > 2 and tags.type_tag < 5 and tags.historizar = true and tags.disabled = false
                and estaciones.id_estacion = " . $id . "
                order by estaciones.nombre_estacion asc";

                $resAnalog = pg_query($this->conexion, $conAnalog);
                if ($this->consultaExitosa($resAnalog)) {
                    $tagsAnalog = pg_fetch_all($resAnalog);
                    $tagsAnalogsHisto[$index->nombre_estacion] = $tagsAnalog;
                }
            }
            return $tagsAnalogsHisto;
        }
        return false;
    }

    //para las fechas vamos a necesitar un traductor de Date() a TimeStamp()
    public function historicosEstacion($id_estacion, $fechaIni, $fechaFin)
    {
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

    public function historicosTagEstacion($id_estacion, $id_tag)
    {
        if ($this->conectar()) {
            $conHistoTagEst = "WITH t as 
            (
             SELECT 
                to_timestamp(round((extract(epoch from fecha)) / 10) * 10)::TIMESTAMP AS ts, 
                AVG(valor_float) AS dob, AVG(valor_acu) AS acu, AVG(valor_int) AS ent
             FROM datos_historicos
             WHERE id_tag = " . $id_tag . " AND fecha::date > current_date::date - interval '7 days'  AND fecha::date < current_date::date
             GROUP BY ts
            ),
            contiguous_ts_list as
            (
             select ts from generate_series(
              (select min(ts) from t),
              (select max(ts) from t), 
              interval '5 minutes'
             ) ts
            )
            select * 
            from contiguous_ts_list 
            left outer join t using (ts)
            order by ts;";

            $resulHistoTagEst = pg_query($this->conexion, $conHistoTagEst);
            if ($this->consultaExitosa($resulHistoTagEst)) {
                $datosHistoTagEst = pg_fetch_all($resulHistoTagEst);
                $datosHisto = array();
                foreach ($datosHistoTagEst as $index => $dato) {
                    foreach ($dato as $factor => $valor) {
                        if ($valor != null && $factor != 'ts') {
                            $datosHisto[$index]['valor'] = number_format($valor, 2);
                        }
                        if ($factor == 'ts') {
                            $datosHisto[$index]['fecha'] = $valor;
                        }
                    }
                }
                //devolver array unico con las "series" y el "meta" del tag
                $seriesTag['tag'] = $datosHisto;
                return $seriesTag;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    //funcion para AJAX, obtiene los historicos de un tag de una estación en un periodo determinado
    //se usa en graficas-> vista personalizada
    public function historicosTagEstacionCustom($id_estacion, $id_tag, $ajustesMeta, $fechaInicio, $fechaFin)
    {
        if ($this->conectar()) {

            $seriesTagCustom = array();
            $metaCustom = array();

            //obtener el metadata del TAG 
            $meta = $this->metaTag($id_tag, $id_estacion);

            // filtrar metadata
            foreach ($ajustesMeta as $index => $tipo) {
                if ($tipo == "maxGen") {
                    $metaCustom['max'] = $meta['max'];
                }
                if ($tipo == "minGen") {
                    $metaCustom['min'] = $meta['min'];
                }
                if ($tipo == "avgGen") {
                    $metaCustom['avg'] = $meta['avg'];
                }
            }

            $seriesTagCustom['meta'] = $metaCustom;

            //traducir fechas(?)

            $ini = strtotime($fechaInicio);
            $fin = strtotime($fechaFin);

            //EXPERIMENTO 8
            //GENERA SERIES PARA ALINEAR LAS LINEAS DE TIEMPO A 5mins 
            $conHistoTagEst = "WITH t as 
            (
             SELECT 
                to_timestamp(round((extract(epoch from fecha)) / 10) * 10)::TIMESTAMP AS ts, 
                AVG(valor_float) AS dob, AVG(valor_acu) AS acu, AVG(valor_int) AS ent
             FROM datos_historicos
             WHERE id_tag = " . $id_tag . " AND cast(extract(epoch from fecha) as integer) < " . $ini . " AND cast(extract(epoch from fecha) as integer) > " . $fin . "
             GROUP BY ts
            ),
            contiguous_ts_list as
            (
             select ts from generate_series(
              (select min(ts) from t),
              (select max(ts) from t), 
              interval '5 minutes'
             ) ts
            )
            select * 
            from contiguous_ts_list 
            left outer join t using (ts)
            order by ts;";


            $resulHistoTagEst = pg_query($this->conexion, $conHistoTagEst);
            if ($this->consultaExitosa($resulHistoTagEst)) {
                $datosHistoTagEst = pg_fetch_all($resulHistoTagEst);
                $datosHisto = array();
                foreach ($datosHistoTagEst as $index => $dato) {
                    foreach ($dato as $factor => $valor) {

                        if ($valor != null && $factor != 'ts') {
                            $datosHisto[$index]['valor'] = number_format($valor, 2);
                        }
                        if ($factor == 'ts') {
                            $datosHisto[$index]['fecha'] = $valor;
                        }
                    }
                }
                //devolver array unico con las "series" y el "meta" del tag
                $seriesTagCustom['tag'] = $datosHisto;
            } else {
                return false;
            }
        } else {
            return false;
        }
        //pasar por caja de cambios el $seriesTagCustom['tag']
        return $seriesTagCustom;
    }

    //secuencia para pasar una alarma de un usuario a estado ACK e incluir nombre del usuario y la fecha de ACK
    //se usa en la sección de alarmas
    public function reconocerAlarma($id_alarma, $usuario, $hora)
    {

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
    public function alarmasSur($id_usuario)
    {
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
    public function alarmasEstacionSur($id_estacion)
    {
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
    public function ultimaComunicacionEstacion($id_estacion)
    {
        if ($this->conectar()) {
            $consulta = "SELECT estaciones.id_estacion, estaciones.nombre_estacion, datos_valores.valor_date, tags.nombre_tag,estaciones.latitud,estaciones.longitud, estaciones.foto  FROM estaciones INNER JOIN estacion_tag ON estaciones.id_estacion = estacion_tag.id_estacion INNER JOIN tags ON tags.id_tag = estacion_tag.id_tag INNER JOIN datos_valores ON estacion_tag.id_tag = datos_valores.id_tag WHERE tags.nombre_tag LIKE 'Ultima Comunicacion%' AND estaciones.id_estacion = " . $id_estacion . " ORDER BY estaciones.nombre_estacion DESC";
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
    public function calidadTagsEstacion($id_estacion)
    {
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
    public function obtenerNombreEstacion($id_estacion)
    {
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
    public function metaTag($id_tag, $id_estacion)
    {
        //si tiene consignas pues las consignas(igual en un futuro)
        if ($this->conectar()) {
            $metaDatos = array();
            $conmaxval = "SELECT MAX(datos_historicos.valor_int), CAST(MAX(datos_historicos.valor_float)*100 AS INT), MAX(datos_historicos.valor_acu) FROM datos_historicos INNER JOIN estacion_tag ON estacion_tag.id_tag = datos_historicos.id_tag WHERE datos_historicos.id_tag = " . $id_tag . " AND estacion_tag.id_estacion = " . $id_estacion . "";
            $conminval = "SELECT MIN(datos_historicos.valor_int),CAST(MIN(datos_historicos.valor_float)*100 AS INT), MIN(datos_historicos.valor_acu) FROM datos_historicos INNER JOIN estacion_tag ON estacion_tag.id_tag = datos_historicos.id_tag WHERE datos_historicos.id_tag = " . $id_tag . " AND estacion_tag.id_estacion = " . $id_estacion . "";
            $conmedia = "SELECT AVG(datos_historicos.valor_int),CAST(AVG(datos_historicos.valor_float)*100 AS INT), AVG(datos_historicos.valor_acu) FROM datos_historicos INNER JOIN estacion_tag ON estacion_tag.id_tag = datos_historicos.id_tag WHERE datos_historicos.id_tag = " . $id_tag . " AND estacion_tag.id_estacion = " . $id_estacion . "";

            $resulmaxval = pg_query($this->conexion, $conmaxval);
            $resulminval = pg_query($this->conexion, $conminval);
            $resulmedia = pg_query($this->conexion, $conmedia);

            if ($this->consultaExitosa($resulmaxval)) {
                $maxval = pg_fetch_all($resulmaxval);
                foreach ($maxval[0] as $index => $valor) {
                    if ($valor != null) {
                        if ($index = 'int4') {
                            $metaDatos['max'] = $valor / 100;
                        } else {
                            $metaDatos['max'] = $valor;
                        }
                    }
                }
            }
            if ($this->consultaExitosa($resulminval)) {
                $minval = pg_fetch_all($resulminval);
                foreach ($minval[0] as $index => $valor) {
                    if ($valor != null) {
                        if ($index = 'int4') {
                            $metaDatos['min'] = $valor / 100;
                        } else {
                            $metaDatos['min'] = $valor;
                        }
                    }
                }
            }
            if ($this->consultaExitosa($resulmedia)) {
                $media = pg_fetch_all($resulmedia);
                foreach ($media[0] as $index => $valor) {
                    if ($valor != null) {
                        if ($index = 'int4') {
                            $metaDatos['avg'] = $valor / 100;
                        } else {
                            $metaDatos['avg'] = $valor;
                        }
                    }
                }
            }
            return $metaDatos;
        } else {
            return false;
        }
    }

    //obtiene los 7 ultimos maximos valores de un tag de una estación
    //hasta que no tengamos comunicación en TR hay que sumar dias al intervalo
    public function tagTrend($id_tag, $id_estacion)
    {
        if ($this->conectar()) {
            $conTrend = "SELECT MAX(datos_historicos.valor_acu) as acu, MAX(datos_historicos.valor_int) as int, MAX(datos_historicos.valor_float) as float, datos_historicos.fecha::date
        from datos_historicos inner join estacion_tag on datos_historicos.id_tag = estacion_tag.id_tag
        where datos_historicos.id_tag = " . $id_tag . " and estacion_tag.id_estacion = " . $id_estacion . "
        and datos_historicos.fecha::date > current_date::date - interval '7 days' GROUP BY datos_historicos.fecha::date LIMIT 7";
            $resTrend = pg_query($this->conexion, $conTrend);
            if ($this->consultaExitosa(($resTrend))) {
                $datosTrendTag = pg_fetch_all($resTrend);
                return ($datosTrendTag);
            }
        }
    }

    //obtiene los informes de un tipo de señal de un grupo de estaciones de un usuario en concreto
    public function informeSeñalEstacion($id_estacion, $señal, $fechaIni, $fechaFin)
    {
        $ini = strtotime($fechaIni);
        $fin = strtotime($fechaFin);

        if ($this->conectar()) {
            if ($señal == 'cau') {
                $tagscaudales = array();
                $informeTags = array();


                $conTagsCaudales = "SELECT tags.nombre_tag, tags.id_tag, tags.unidad
                FROM tags INNER JOIN estacion_tag ON tags.id_tag = estacion_tag.id_tag
                WHERE id_estacion = " . $id_estacion . " AND tags.nombre_tag LIKE('Caudal%')";

                $resTagsCaudales = pg_query($this->conexion, $conTagsCaudales);
                if ($this->consultaExitosa($resTagsCaudales)) {

                    $tagscaudales = pg_fetch_all($resTagsCaudales);
                    foreach ($tagscaudales as $index => $tag) {

                        $conAgregTag = "SELECT MAX(datos_historicos.valor_float) as maximo, MIN(datos_historicos.valor_float) as minimo, cast(AVG(datos_historicos.valor_float) as numeric(10,2)) as media, datos_historicos.fecha::date
                        from datos_historicos inner join estacion_tag on datos_historicos.id_tag = estacion_tag.id_tag
                        where datos_historicos.id_tag = " . $tag['id_tag'] . " and estacion_tag.id_estacion = " . $id_estacion . "AND cast(extract(epoch from datos_historicos.fecha) as integer) <= " . $ini . " AND cast(extract(epoch from datos_historicos.fecha) as integer) > " . $fin . " 
                        GROUP BY datos_historicos.fecha::date ORDER BY datos_historicos.fecha desc";

                        $resAgregTag = pg_query($this->conexion, $conAgregTag);
                        if ($this->consultaExitosa($resAgregTag)) {
                            if ($tag['unidad'] != null) {
                                $nombretag = $tag['nombre_tag'] . " (" . $tag['unidad'] . ")";

                                $informeTags[$nombretag] = pg_fetch_all($resAgregTag);
                            } else {
                                $informeTags[$tag['nombre_tag']] = pg_fetch_all($resAgregTag);
                            }
                        }
                    }
                }
                return $informeTags;
            }

            if ($señal == 'niv') {
                $tagscaudales = array();
                $informeTags = array();


                $conTagsCaudales = "SELECT tags.nombre_tag, tags.id_tag, tags.unidad
                FROM tags INNER JOIN estacion_tag ON tags.id_tag = estacion_tag.id_tag
                WHERE id_estacion = " . $id_estacion . " AND tags.nombre_tag LIKE('Nivel%')";

                $resTagsCaudales = pg_query($this->conexion, $conTagsCaudales);
                if ($this->consultaExitosa($resTagsCaudales)) {

                    $tagscaudales = pg_fetch_all($resTagsCaudales);
                    foreach ($tagscaudales as $index => $tag) {

                        $conAgregTag = "SELECT MAX(datos_historicos.valor_float) as maximo, MIN(datos_historicos.valor_float) as minimo, cast(AVG(datos_historicos.valor_float) as numeric(10,2)) as media, datos_historicos.fecha::date
                        from datos_historicos inner join estacion_tag on datos_historicos.id_tag = estacion_tag.id_tag
                        where datos_historicos.id_tag = " . $tag['id_tag'] . " and estacion_tag.id_estacion = " . $id_estacion . "AND cast(extract(epoch from datos_historicos.fecha) as integer) <= " . $ini . " AND cast(extract(epoch from datos_historicos.fecha) as integer) > " . $fin . " 
                        GROUP BY datos_historicos.fecha::date ORDER BY datos_historicos.fecha::date desc";

                        $resAgregTag = pg_query($this->conexion, $conAgregTag);
                        if ($this->consultaExitosa($resAgregTag)) {
                            if ($tag['unidad'] != null) {
                                $nombretag = $tag['nombre_tag'] . " (" . $tag['unidad'] . ")";

                                $informeTags[$nombretag] = pg_fetch_all($resAgregTag);
                            } else {
                                $informeTags[$tag['nombre_tag']] = pg_fetch_all($resAgregTag);
                            }
                        }
                    }
                }

                return $informeTags;
            }

            if ($señal == 'acu') {
                $tagscaudales = array();
                $informeTags = array();


                $conTagsCaudales = "SELECT tags.nombre_tag, tags.id_tag, tags.unidad 
                FROM tags INNER JOIN estacion_tag ON tags.id_tag = estacion_tag.id_tag
                WHERE id_estacion = " . $id_estacion . " AND tags.nombre_tag LIKE('Acumulado%') AND tags.nombre_tag LIKE('%Dia')";

                $resTagsCaudales = pg_query($this->conexion, $conTagsCaudales);
                if ($this->consultaExitosa($resTagsCaudales)) {

                    $tagscaudales = pg_fetch_all($resTagsCaudales);
                    foreach ($tagscaudales as $index => $tag) {

                        $conAgregTag = "SELECT MAX(datos_historicos.valor_acu) as valor, datos_historicos.fecha::date
                        from datos_historicos inner join estacion_tag on datos_historicos.id_tag = estacion_tag.id_tag
                        where datos_historicos.id_tag = " . $tag['id_tag'] . " and estacion_tag.id_estacion = " . $id_estacion . "AND cast(extract(epoch from datos_historicos.fecha) as integer) <= " . $ini . " AND cast(extract(epoch from datos_historicos.fecha) as integer) > " . $fin . " 
                        GROUP BY datos_historicos.fecha::date ORDER BY datos_historicos.fecha::date desc";

                        $resAgregTag = pg_query($this->conexion, $conAgregTag);
                        if ($this->consultaExitosa($resAgregTag)) {
                            if ($tag['unidad'] != null) {
                                $nombretag = $tag['nombre_tag'] . " (" . $tag['unidad'] . ")";

                                $informeTags[$nombretag] = pg_fetch_all($resAgregTag);
                            } else {
                                $informeTags[$tag['nombre_tag']] = pg_fetch_all($resAgregTag);
                            }
                        }
                    }
                }

                return $informeTags;
            }

            if ($señal == "clo") {
                $tagscaudales = array();
                $informeTags = array();

                $conTagsCaudales = "SELECT tags.nombre_tag, tags.id_tag, tags.unidad
                FROM tags INNER JOIN estacion_tag ON tags.id_tag = estacion_tag.id_tag
                WHERE id_estacion = " . $id_estacion . " 
                AND tags.nombre_tag LIKE('Cloro%')
                OR tags.nombre_tag LIKE('Turbidez%')";

                $resTagsCaudales = pg_query($this->conexion, $conTagsCaudales);
                if ($this->consultaExitosa($resTagsCaudales)) {

                    $tagscaudales = pg_fetch_all($resTagsCaudales);
                    foreach ($tagscaudales as $index => $tag) {

                        $conAgregTag = "SELECT MAX(datos_historicos.valor_float) as maximo, MIN(datos_historicos.valor_float) as minimo, cast(AVG(datos_historicos.valor_float) as numeric(10,2)) as media, datos_historicos.fecha::date
                        from datos_historicos inner join estacion_tag on datos_historicos.id_tag = estacion_tag.id_tag
                        where datos_historicos.id_tag = " . $tag['id_tag'] . " and estacion_tag.id_estacion = " . $id_estacion . "AND cast(extract(epoch from datos_historicos.fecha) as integer) <= " . $ini . " AND cast(extract(epoch from datos_historicos.fecha) as integer) > " . $fin . " 
                        GROUP BY datos_historicos.fecha::date ORDER BY datos_historicos.fecha::date desc";

                        $resAgregTag = pg_query($this->conexion, $conAgregTag);
                        if ($this->consultaExitosa($resAgregTag)) {
                            if ($tag['unidad'] != null) {
                                $nombretag = $tag['nombre_tag'] . " (" . $tag['unidad'] . ")";

                                $informeTags[$nombretag] = pg_fetch_all($resAgregTag);
                            } else {
                                $informeTags[$tag['nombre_tag']] = pg_fetch_all($resAgregTag);
                            }
                        }
                    }
                }

                return $informeTags;
            }
        }
    }

    //obtiene toda la informacion de las señales digitales de inicio
    //busca tags digitales con alarma en un periodo de 48h
    public function feedPrincipalDigital($estaciones)
    {
        if ($this->conectar()) {

            //recorrer estaciones y sacar tags digitales
            //ver si esa estación tiene alarmas activas recientes de tags digitales y coger la mas reciente
            //guardarlas en un array


            $feed = array();
            foreach ($estaciones as $index => $estacion) {
                $id_estacion = $estacion['id_estacion'];

                $conTagsDigi = "SELECT tags.id_tag, tags.nombre_tag 
                FROM estacion_tag INNER JOIN tags ON tags.id_tag = estacion_tag.id_tag 
                WHERE estacion_tag.id_estacion = $id_estacion AND tags.type_tag = 1";

                $resTagsDigi = pg_query($this->conexion, $conTagsDigi);
                if ($this->consultaExitosa($resTagsDigi)) {
                    $tagsDigiEstacion = pg_fetch_all($resTagsDigi);

                    foreach ($tagsDigiEstacion as $index => $tag) {
                        $id = $tag['id_tag'];
                        $conAlarma = "SELECT fecha_origen, id_tag, valor_alarma 
                        FROM alarmas 
                        WHERE estado = 1 AND id_tag = " . $id . " AND fecha_origen::date > current_date::date - interval '1 days' 
                        AND NOT valor_alarma = '' 
                        ORDER BY fecha_origen DESC LIMIT 1";

                        $resAlarmas = pg_query($this->conexion, $conAlarma);
                        if ($this->consultaExitosa($resAlarmas)) {
                            $alarmasTagDigi = pg_fetch_all($resAlarmas);
                            $alarmasTagDigi[0]['nombre'] = $tag['nombre_tag'];
                            $feed[$estacion['nombre_estacion']][$id] = $alarmasTagDigi[0];
                        }
                    }
                } else {
                    return false;
                }
            }
            return $feed;
        } else {
            return false;
        }
    }

    //función para guardar la configuracion de usuario en los ajustes de inicio
    //Ejemplo de codigo de config --> "w1:126-w2:260-w3:261-w4:167";
    public function confirmarWidget($wid, $tag, $id_usuario)
    {
        $configBD = "";
        $configuracionWidgetsUsuario = array();
        if ($this->conectar()) {
            $configVieja = $this->obtenerConfigInicio($id_usuario);
            if ($configVieja) {
                $configArr = explode("-", $configVieja['configuracion_inicio']);
                foreach ($configArr as $index => $configWid) {
                    $arrConfigWid = explode(":", $configWid);
                    $configuracionWidgetsUsuario[$arrConfigWid[0]] = $arrConfigWid[1];
                }
                if ($wid == 'w1') {
                    $configuracionWidgetsUsuario['w1'] = $tag;
                }
                if ($wid == 'w2') {
                    $configuracionWidgetsUsuario['w2'] = $tag;
                }
                if ($wid == 'w3') {
                    $configuracionWidgetsUsuario['w3'] = $tag;
                }
                if ($wid == 'w4') {
                    $configuracionWidgetsUsuario['w4'] = $tag;
                }
            } else {
                $configuracionWidgetsUsuario = ['w1' => '', 'w2' => '', 'w3' => '', 'w4' => ''];
            }
            $configBD = "w1:" . $configuracionWidgetsUsuario['w1'] . "-w2:" . $configuracionWidgetsUsuario['w2'] . "-w3:" . $configuracionWidgetsUsuario['w3'] . "-w4:" . $configuracionWidgetsUsuario['w4'];
            $secuencia = "UPDATE usuarios SET configuracion_inicio = '" . $configBD . "' WHERE id_usuario = " . $id_usuario;
            $envio = pg_query($this->conexion, $secuencia);
            if ($this->consultaExitosa($envio)) {
                return true;
            }
        }
        return false;
    }

    //obtiene la configuracion de widgets de un usuario
    //es una funcion para feedPrincipalCustom
    private function obtenerConfigInicio($id_usuario)
    {
        if ($this->conectar()) {
            $consulta = "SELECT configuracion_inicio FROM usuarios WHERE id_usuario = " . $id_usuario . "";
            $res = pg_query($this->conexion, $consulta);
            if ($this->consultaExitosa($res)) {
                $config = pg_fetch_all($res)[0];
                return $config;
            }
        }
        return false;
    }

    //obtiene el nombre de un tag concreto
    //uso interno
    private function obtenerNombreTag($id_tag)
    {
        if ($this->conectar()) {
            $con = "SELECT nombre_tag FROM tags WHERE id_tag = " . $id_tag;
            $res = pg_query($this->conexion, $con);
            if ($this->consultaExitosa($res)) {
                return pg_fetch_all($res)[0]['nombre_tag'];
            }
        }
    }

    //obtiene (si existen) las consignas de un tag
    //uso interno
    private function obtenerConsignasTag($id_tag)
    {

        $nombre_tag = $this->obtenerNombreTag($id_tag);

        if ($this->conectar()) {
            $con = "SELECT estaciones.nombre_estacion,tags.nombre_tag,tags.unidad, datos_valores.valor_float 
            from datos_valores inner join tags on tags.id_tag = datos_valores.id_tag 
            inner join estacion_tag on estacion_tag.id_tag = tags.id_tag
            inner join estaciones on estaciones.id_estacion = estacion_tag.id_estacion
            WHERE tags.nombre_tag LIKE('Consigna " . $nombre_tag . "%') and estaciones.id_estacion = (select id_estacion from estacion_tag where id_tag = " . $id_tag . ")
            order by estaciones.nombre_estacion";
            $res = pg_query($this->conexion, $con);
            if ($this->consultaExitosa($res)) {
                return pg_fetch_all($res);
            }
        }
        return false;
    }
    //obtiene el ultimo dato, el trend diario y los agregados semanales de los widgets definidos por el 
    //usuario en su configuracion
    public function feedPrincipalCustom($id_usuario)
    {
        if ($this->conectar()) {
            $configuracionWidgetsUsuario = array();
            $config = $this->obtenerConfigInicio($id_usuario);
            if ($config) {
                $configArr = explode("-", $config['configuracion_inicio']);
                foreach ($configArr as $index => $configWid) {
                    $arrConfigWid = explode(":", $configWid);
                    $configuracionWidgetsUsuario[$arrConfigWid[0]] = $arrConfigWid[1];
                }
            }

            $ultvalor = "";
            $trendDia = array();
            $agregSemana = array();
            $infoTag = array();
            $consignas_tag = array();

            foreach ($configuracionWidgetsUsuario as $widget => $tag) {
                $tag = intval($tag);
                $consignas_tag = $this->obtenerConsignasTag($tag);
                //ultimo valor del tag
                $conUltimoValor = "SELECT tags.unidad,tags.r_min,tags.r_max,estaciones.nombre_estacion, tags.nombre_tag, datos_valores.valor_acu, datos_valores.valor_float,datos_valores.valor_int,datos_valores.id_tag,datos_valores.fecha 
                FROM datos_valores inner join tags on tags.id_tag = datos_valores.id_tag
                inner join estacion_tag on tags.id_tag = estacion_tag.id_tag
                inner join estaciones on estaciones.id_estacion = estacion_tag.id_estacion
                WHERE datos_valores.id_tag=" . $tag;
                $resUltimoValor = pg_query($this->conexion, $conUltimoValor);
                if ($this->consultaExitosa($resUltimoValor)) {
                    $ultvalor = pg_fetch_all($resUltimoValor)[0];
                    $ultValorLimpio = array();
                    foreach ($ultvalor as $factor => $valor) {
                        if (str_contains($factor, 'valor_')) {
                            if ($valor != null) {
                                $ultValorLimpio['valor'] = $valor;
                            }
                        } else {
                            $ultValorLimpio[$factor] = $valor;
                        }
                    }
                    $ultvalor = $ultValorLimpio;
                } else {
                    $ultvalor = false;
                }

                //trend diario (o semanal si es acumulado) del tag
                $conTrendDia = "";
                $n_tag = $this->obtenerNombreTag($tag);
                if (strpos($n_tag, 'Acumulado') !== false) {
                    $conTrendDia = "SELECT datos_historicos.fecha, datos_historicos.valor_acu, datos_historicos.valor_float, valor_int FROM datos_historicos WHERE id_tag=" . $tag . " AND datos_historicos.fecha::date >= current_date::date - interval '7 days' AND datos_historicos.fecha::date <= current_date::date ORDER BY fecha desc";
                } else {
                    $conTrendDia = "SELECT datos_historicos.fecha::time, datos_historicos.valor_acu, datos_historicos.valor_float, valor_int FROM datos_historicos WHERE id_tag=" . $tag . " AND datos_historicos.fecha::date >= current_date::date - interval '1 days' AND datos_historicos.fecha::date <= current_date::date ORDER BY fecha desc";
                }
                $resTrendDia = pg_query($this->conexion, $conTrendDia);
                if ($this->consultaExitosa($resTrendDia)) {
                    $trendDia = pg_fetch_all($resTrendDia);
                    $trendDiaLimpio = array();
                    $ultVal = null;
                    foreach ($trendDia as $index => $dato) {
                        foreach ($dato as $factor => $valor) {
                            if (str_contains($factor, 'valor_')) {
                                if ($valor != null) {
                                    $trendDiaLimpio[$index]['valor'] = $valor;
                                    $ultVal = $valor;
                                } else {
                                    $trendDiaLimpio[$index]['valor'] = $ultVal;
                                }
                            } else {
                                $trendDiaLimpio[$index][$factor] = $valor;
                            }
                        }
                    }
                    $trendDia = $trendDiaLimpio;
                } else {
                    $trendDia = false;
                }

                //trend semanal de agregados (o solo maximos y 2 semanas si es acumulado) del tag

                $conAgregSemanal = "";
                if (strpos($n_tag, 'Acumulado') !== false) {
                    $conAgregSemanal = "SELECT MAX(datos_historicos.valor_acu) as max_acu, MAX(datos_historicos.valor_int) as max_int, MAX(datos_historicos.valor_float) as max_float,datos_historicos.fecha::date
                    from datos_historicos inner join estacion_tag on datos_historicos.id_tag = estacion_tag.id_tag
                    where datos_historicos.id_tag = " . $tag . "
                    and datos_historicos.fecha::date > current_date::date - interval '14 days' AND datos_historicos.fecha::date <= current_date::date GROUP BY datos_historicos.fecha::date LIMIT 14";

                    $resAgregSemanal = pg_query($this->conexion, $conAgregSemanal);
                    if ($this->consultaExitosa($resAgregSemanal)) {
                        $agregSemana = pg_fetch_all($resAgregSemanal);
                        $agregSemanaLimpio = array();
                        foreach ($agregSemana as $index => $dato) {
                            foreach ($dato as $factor => $valor) {
                                if ($valor != null && $factor != 'fecha') {
                                    if (strpos($factor, 'max') !== false) {
                                        $agregSemanaLimpio[$index]['max'] = $valor;
                                    }
                                } else {
                                    $agregSemanaLimpio[$index][$factor] = $valor;
                                }
                            }
                        }
                        $agregSemana = $agregSemanaLimpio;
                    }
                } else {
                    $conAgregSemanal = "SELECT MAX(datos_historicos.valor_acu) as max_acu, MAX(datos_historicos.valor_int) as max_int, MAX(datos_historicos.valor_float) as max_float,
                    MIN(datos_historicos.valor_acu) as min_acu, MIN(datos_historicos.valor_int) as min_int, MIN(datos_historicos.valor_float) as min_float,
                    AVG(datos_historicos.valor_acu) as avg_acu, AVG(datos_historicos.valor_int) as avg_int, AVG(datos_historicos.valor_float) as avg_float,datos_historicos.fecha::date
                    from datos_historicos inner join estacion_tag on datos_historicos.id_tag = estacion_tag.id_tag
                    where datos_historicos.id_tag = " . $tag . "
                    and datos_historicos.fecha::date > current_date::date - interval '7 days' AND datos_historicos.fecha::date <= current_date::date GROUP BY datos_historicos.fecha::date LIMIT 7";

                    $resAgregSemanal = pg_query($this->conexion, $conAgregSemanal);
                    if ($this->consultaExitosa($resAgregSemanal)) {
                        $agregSemana = pg_fetch_all($resAgregSemanal);
                        $agregSemanaLimpio = array();
                        foreach ($agregSemana as $index => $dato) {
                            foreach ($dato as $factor => $valor) {
                                if ($valor != null && $factor != 'fecha') {
                                    if (strpos($factor, 'max') !== false) {
                                        $agregSemanaLimpio[$index]['max'] = $valor;
                                    }
                                    if (strpos($factor, 'min') !== false) {
                                        $agregSemanaLimpio[$index]['min'] = $valor;
                                    }
                                    if (strpos($factor, 'avg') !== false) {
                                        $agregSemanaLimpio[$index]['avg'] = $valor;
                                    }
                                } else {
                                    $agregSemanaLimpio[$index][$factor] = $valor;
                                }
                            }
                        }
                        $agregSemana = $agregSemanaLimpio;
                    } else {
                        $agregSemana = false;
                    }
                }

                if ($consignas_tag != false) {
                    $infoTag[$widget] = ["unidad" => $ultvalor['unidad'], "consignas" => $consignas_tag, "widget" => $widget, "nombre" => $ultvalor['nombre_tag'], "estacion" => $ultvalor['nombre_estacion'], "ultimo_valor" => $ultvalor, "trend_dia" => $trendDia, "agreg_semana" => $agregSemana];
                } else {
                    $infoTag[$widget] = ["unidad" => $ultvalor['unidad'], "widget" => $widget, "nombre" => $ultvalor['nombre_tag'], "estacion" => $ultvalor['nombre_estacion'], "ultimo_valor" => $ultvalor, "trend_dia" => $trendDia, "agreg_semana" => $agregSemana];
                }
            }
            return $infoTag;
        }
    }

    //funcion para la seccion de graficosCustom. Borra un preset seleccionado del usuario
    public function borrarPreset($n_preset, $id_usuario)
    {
        if ($this->conectar()) {
            $sec = "DELETE FROM graficas WHERE id_usuario = " . $id_usuario[0]['id_usuario'] . " AND configuracion LIKE('" . $n_preset . "%')";
            pg_query($this->conexion, $sec);
            return true;
        } else {
            return false;
        }
    }

    //obtiene la lista de presets guardada de un usuario
    public function leerPresets($id_usuario)
    {
        if ($this->conectar()) {
            $conPresets = "SELECT configuracion FROM graficas WHERE id_usuario = " . $id_usuario[0]['id_usuario'] . "";
            $resPresets = pg_query($this->conexion, $conPresets);
            if ($this->consultaExitosa($resPresets)) {
                $presets = pg_fetch_all($resPresets);
                return $presets;
            }
        }
        return false;
    }

    // guarda un preset nuevo para un usuario
    //ejemplo de codigo --> nombre@6?/1:12#fffff-23#gggg-45#kkkkk/2:xxxxxx/3:xxxxx
    //estructura de config --> nombre@id_estacion?/tag:color-tag:color-tag:color-
    public function guardarPreset($usuario, $pwd, $nombre, $estacion, $tags_colores)
    {
        $codigo = $nombre . "@" . $estacion . "?";
        foreach ($tags_colores as $tag => $color) {
            if ($color != null) {
                $codigo .= "/" . $tag . ":" . $color . "";
            }
        }
        $id_usuario = $this->obtenerIdUsuario($usuario, $pwd);
        if ($id_usuario) {
            $secu = "INSERT INTO graficas(id_usuario, configuracion)
            VALUES (" . $id_usuario[0]['id_usuario'] . ", '" . $codigo . "')";
            pg_query($this->conexion, $secu);
            return true;
        }
        return false;
    }
}