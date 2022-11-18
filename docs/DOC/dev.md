<div style='background-colorrgb(238,238,228);padding:2em;border:5px solid gray;border-radius:1em;'>

# DOCUMENTACION DE AQUANDO PARA DESARROLLADORES

## Manual básico de desarrollador para Aquando.com

---

### **Contenidos:** :book:

- **General**
  - Información
  - Arquitectura general
  - Dependencias
- **Controlador**
  - Inicio.php
- **Bases de datos**
  - Database.php
  - DataWit.php
- **Modelos**
  - Contras.php
  - Usuario.php
  - Validador.php
- **Vistas**
  - Inicio
  - Estaciones
  - Alarmas
  - Graficas
  - Informes
  - Comunicaciones
  - Otros
- **Lado cliente**
  - JavaScript
  - Ajax

---

## GENERAL

### Información básica:

> Aquando es una aplicación API-REST escrita mayormente en PHP y JavaScritpt.
> Se apoya en la plataforma CodeIgniter para organizarla en un estándar de producción común.

Básicamente, Aquando consiste en un back-end que va a escuchar las distintas peticiones que se realicen desde cliente. Dependiendo de estas, desde el controlador se decide que hacer y como.
Dependiendo del tipo de petición en ocasiones no será el controlador lo que procese las peticiones sino archivos en PHP también que se comunican con el cliente a traves de AJAX. Estos archivos existen dedicados para cada sección y realizan acciones sencillas en tiempo real funcionando como pequeños controladores para las secciones.
El resto de acciones (normamente gráficas y estéticas) son realizadas desde el lado cliente con JavaScript siempre que no requieran de archivos del servidor ni de las bases de datos.

### Dependencias:

**SERVIDOR**

- Apache2 en entorno Linux o XAMPP en Windows.
- Extensiones para PHP 7+
  - sqlsrv
  - pgsql
- CodeIgniter 4
- KoolReport

**CLIENTE**

- fontAwesome
- jquery
- echarts
- leaflet
- html2pdf
- html2canvas

---

## **1. CONTROLADOR**

### **CLASE Inicio.php**

El controlador se llama Inicio.php y se ejecuta despues de BaseController.php y el resto de componentes de codeIgniter.

El controlador es el componente que gestiona las peticiones que llegan desde lado cliente. Pero también se encarga de instanciar la aplicación por primera vez cuando esta empieza a funcionar.

Para funcionar primero implementa las clases sobre las que se apoya para procesar la actividad en el servidor. Estas clases se explican mas en detalle en sus secciones mas adelante. Las clases son Usuario.php. Contras.php y Validador.php que se encuentran bajo la carpeta Models.
También en el contrusctor de clase, instancian losetos usuario, sesion y vlr (validador).

### FUNCION **index()**

> @returns View

Esta función lo que hace es arrancar la aplicación. También muestra la sección principal en caso de que la sesión exista y muestra el Log-out si la sesión ha expirado.

```php
    public function index()
    {
        $_SESSION['seccion'] = 'inicio';
        if (isset($_GET['log']) && $_GET['log'] == 'out') {
            session_unset();
            $_SESSION['mensajeDesc'] = true;
            $_SESSION['seccion'] = '';
            return view('inicio');
        } else {
            if (isset($_SESSION['nombre'])) {
                $this->usuario = new Usuario($_SESSION['nombre'], $_SESSION['pwd']);
                $_SESSION['nombre_cliente'] = $this->usuario->getCliente();
                $datos['estaciones'] = $this->usuario->obtenerEstacionesUsuario($_SESSION['hpwd']);
                $_SESSION['estaciones'] = $datos['estaciones'];
                $datos['estacionesUbis'] = $this->usuario->ultimasConexiones();
                $_SESSION['seccion'] = "prin";
                return view('principal', $datos);
            } else {
                session_unset();
                $_SESSION['seccion'] = "";
                $_SESSION['mensajeDesc'] = true;
                return view('inicio');
            }
        }
    }

```

### FUNCION **inicioSesion()**

> @returns View

Esta función devuelve la vista de incio de sesión y en caso de que está ya estuviera definida, la destruye.También se encarga de comprobar si las credenciales son correctas y de validarlas.

```php
        public function inicioSesion()
            {
                $_SESSION['seccion'] = "login";
                if (isset($_SESSION['nombre'])) {
                    session_unset();
                }
                $_SESSION['mensajeDesc'] = false;
                $nombre = "";
                $pwd = "";
                if (isset($_POST["txtNombre"]) && isset($_POST["txtContrasena"])) {
                    $nombre = $_POST["txtNombre"];
                    $pwd = $_POST["txtContrasena"];
                    //EXPERIMENTAL: VALIDADOR DE INPUTS
                    if((!$this->vlr->valLog($nombre)) || !($this->vlr->valLog($pwd)) ){
                        echo '<script language="javascript">alert("carácteres no válidos")</script>';
                                return view('inicioSesion');
                    }
                    $this->usuario = new Usuario($nombre, $pwd);
                    //comrpueba que exista un usuario con ese nombre y en ese caso verifica contraseñas
                    if ($this->usuario->existeUsuario() == true) {
                        //mirar contra y eso
                        $id_usu = $this->usuario->obtenerIdUsuario($nombre);
                        if ($id_usu != null) {
                            $conSys = new Contras($id_usu);
                            //echo $conSys->hashear($pwd);
                            if ($conSys->loginUsuario($pwd)) {
                                $_SESSION['hpwd'] = $conSys->getHash();
                                $_SESSION['estaciones'] = $this->usuario->obtenerEstacionesUsuario($_SESSION['hpwd']);
                                $_SESSION['nombre'] = $nombre;
                                $_SESSION['pwd'] = $pwd;
                                return $this->index();
                            } else {
                                echo '<script language="javascript">alert("Contraseña incorrecta")</script>';
                                return view('inicioSesion');
                            }
                        } else {
                            echo '<script language="javascript">alert("Datos incorrectos")</script>';
                            return view('inicioSesion');
                        }
                    } else {
                        echo '<script language="javascript">alert("Usuario desconocido")</script>';
                        return view('inicioSesion');
                    }
                } else {
                    return view('inicioSesion');
                }
            }
```

### FUNCION **estacion()**

> @returns View

Esta fucnión devuelve la vista de estación. Dependiendo de la información adquirida por POST, comprobará si esa estación existe, si pertenece al usuario y en caso afirmativo, devuelve la vista estación con sus datos.

```php
    public function estacion()
    {
        if (isset($_SESSION['nombre'])) {
            $_SESSION['seccion'] = "estacion";
            $usuario = new Usuario($_SESSION['nombre'], $_SESSION['pwd']);
            foreach ($_SESSION['estaciones'] as $index => $estacion) {
                if ($estacion["id_estacion"] == $_POST["btnEstacion"]) {
                    $nombreEstacion = $estacion["nombre_estacion"];
                    $datos['id_estacion'] = $estacion["id_estacion"];
                }
            }
            $ultimaConex = $usuario->ultimaConexionEstacion($_POST["btnEstacion"]);
            if ($ultimaConex != false) {
                $datos['ultimaConex'] = $ultimaConex;
            } else {
                $datos['ultimaConex'] = "error";
            }
            $datos['nombreEstacion'] = $nombreEstacion;
            return view('estacion', $datos);
        } else {
            return view('inicio');
        }
    }

```

### FUNCION **graficas()**

> @returns View

Esta función devuelve la sección de vista rápida o personalizada dependiendo de la información que le llegue por POST.

```php

public function graficas()
    {
        if (isset($_SESSION['nombre'])) {
            $_SESSION['seccion'] = "graficos";
            $usuario = new Usuario($_SESSION['nombre'], $_SESSION['pwd']);
            $datos['tagsEstaciones'] = $usuario->obtenerTagsEstaciones($usuario->obtenerEstacionesUsuario($_SESSION['hpwd']));
            if (isset($_POST['btnGraf']) && $_POST['btnGraf'] == 'rapida') {
                return view('graficas', $datos);
            } else {
                return view('graficasCustom', $datos);
            }
        } else {
            return view('inicio');
        }
    }

```

### FUNCION **alarmas()**

> @returns View

Esta función devuelve la vista de alarmas con las alarmas pertenecientes a un usuario desde hace un mes\*.

```php

public function alarmas()
    {
        if (isset($_SESSION['nombre'])) {
            $_SESSION['seccion'] = "alarmas";
            if (isset($_SESSION['alarmas'])) {
                $datos['alarmas'] = $_SESSION['alarmas'];
            } else {
                $this->usuario = new Usuario($_SESSION['nombre'], $_SESSION['pwd']);
                //alarmas desde un mes
                $estaciones = $this->usuario->obtenerEstacionesUsuario($_SESSION['hpwd']);
                $datos['estaciones'] = $estaciones;
            }
            return view('alarmas', $datos);
        } else {
            return view('inicio');
        }
    }

```

### FUNCION **informes()**

> @returns View

Esta fucnión devuelve la vista de informes

```php

public function informes()
    {
        if (isset($_SESSION['nombre'])) {
            $_SESSION['seccion'] = "infos";
            return view('informes');
        } else {
            return $this->inicioSesion();
        }
    }

```

### FUNCION **comunicaciones()**

> @returns View

Esta funcion devuelve la vista de comunicaciones

```php

public function comunicaciones()
    {
        $_SESSION['seccion'] = "coms";
        if (isset($_SESSION['nombre'])) {
            return view('comunicaciones');
        } else {
            return view('inicio');
        }
    }

```

Y con esto hemos cubierto la totalidad del controlador. El funcionamiento de las clases de apoyo se explican mas adelante y el funcionamiento asi como enrutamiento están en la documentación de codeIgniter.

---

## **2. BASES DE DATOS**

IMPORTANTE: Estas clases son provisionales antes de implementar otras tecnologías mas potentes como Apache Spark o Fiware.
El sistema de bases de datos se compone por dos clases: Database y DataWit.
Database es la mas grande de las dos y se usa para prácticamente todo. En ella se encuentran las interaciones con PostgreSQL.
Datawit es una clase que conecta con dos bases de datos en SQL Server para ver y editar consignas asi como plannings de tiempo para algunas estaciones que por limitaciones en las comunicaciones no se podía hacer desde PostgreSQL

## **2.1 CLASE Database.php**

> importante: esta clase se apoya en los drivers de PostgreSQL para PHP (pgsql) no incluidos en apache

Por lo general en Database vamos a hacer consultas a PostgreSQL en función de unos parámetros incluidos en las llamadas a esta clase. Estas llamadas tienen varios origenes incluyendo el Controlador y los archivos de AJAX

Nada mas instanciar la clase, se inician unas constantes con las credenciales y configuración de conexión a la base de datos.
En el constructor tambien se instancia una funcion que en versiones mas recientes de PHP ya está incluida pero que por limitaciones de Debian, al tener que usar PHP5 necesitamos.

### FUNCION **conectar()**

> @returns Mixed: conexion | bool

Esta función establece la conexión entre la aplicación y la base de datos.
En ese caso devulve un objeto _SQLSTMT_ de conexion.
En caso de error, devuelve _false_.
Su uso es puramente interno y es llamada en casi todas las funciones para comprobar que esta funciona.

```php
private function conectar()
    {
        if (!$this->conexion) {
            return $this->conexion = pg_connect("host=$this->host dbname=$this->dbname user=$this->user password=$this->password");
        }
        return $this->conexion;
    }
```

### FUNCION **consultaExitosa()**

> @params SQLSTMT resultado

> @returns bool

Esta es otra funcion interna a la cual se llama cada vez que se produce una consulta a la base de datos y se encarga de comprobar si el objeto _SQLSTMT_ devulto por PostgreSQL es válido y contiene información.

```php
private function consultaExitosa($resultado)
    {
        $nResuls = pg_num_rows($resultado);
        if ($nResuls != 0 || $nResuls != null) {
            return true;
        } else {
            return false;
        }
    }
```

### FUNCION **obtenerNombreTag()**

> @params INT id_tag

> @returns Mixed String | Bool

Funcion interna que devulve el nombre de un tag dado su id

```php
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

```

### FUNCION **obtenerConsignasTag()**

> @params INT id_tag

> @returns Mixed Array | Bool

Funcion interna que devulve las consignas de un tag dado su id

```php
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
```

### FUNCION **obtenerIdUsuario()**

> @params String nombre_usuario

> @returns Mixed INT | Bool

Esta es una función pública que devuelve el id_usuario dado un nombre. Tiene un uso muy limitado y es de apoyo a otras funciones.

```php
{
        if ($this->conectar()) {
            $consulta = "SELECT id_usuario FROM usuarios WHERE nombre ='$nombre'";
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
```

### FUNCION obtenerClienteUsuario()

> @params String nombre_usuario

> @returns Mixed String | Bool

Función pública que devuelve el cliente o grupo al que pertenece un usuario dado su nombre. Su uso es limitado y es de apoyo a otras funciones.

```php
public function obtenerClienteUsuario($nombre_usuario)
    {
        $id_usuario = $this->obtenerIdUsuario($nombre_usuario)[0]['id_usuario'];
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
```

### FUNCION **existeUsusario()**

> @params String nombre

> @returns Bool

Función pública que comprueba la existencia de un usuario dado su nombre. Su uso es limitado y sirve de apoyo a otras funciones.

```php
public function existeUsuario($nombre)
    {
        if ($this->conectar()) {
            $consulta = "SELECT * FROM public.usuarios WHERE nombre ='$nombre'";
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
```

### FUNCION **userData()**

> @params INT id_usu

> @returns Mixed Array | Bool

Función pública parte del sistema de Log-in que recoge información necesaria para la clase Contras.php dado el id de un usuario.
Sólo se usa en el contructor de Contras.php

```php
 public function userData($id_usu)
    {
        $uData = null;
        if ($this->conectar()) {
            $con = "SELECT * FROM usuarios WHERE id_usuario =" . $id_usu . "LIMIT 1";
            $res = pg_query($this->conexion, $con);
            if ($this->consultaExitosa($res)) {
                $uData = pg_fetch_all($res);
                return $uData;
            }
        }
        return false;
    }

```

### FUNCION **updateUserData()**

> @params INT id_usu, String pwd

> @returns Bool

Función pública de la que se apoya Contras.php cuando debe actualizar el cifrado de un HASH de un usuario especifico.

```php
public function updateUserData($id_usu, $pwd)
    {
        if ($this->conectar()) {
            $con = "UPDATE usuarios SET usuarios.hash = " . $pwd . " WHERE usuarios.id_usuario = $id_usu";
            $res = pg_query($this->conexion, $con);
            if ($this->consultaExitosa($res)) {
                return true;
            }
        }
        return false;
    }
```

### FUNCION **mostrarEstacionesCliente()**

> @params String nombre, String pwd

> @returns Mixed Array | Bool

Esta función pública recoge toda la información de las propiedades de todas las estaciones que pertenezcan a un usuario dado su nombre y contraseña cifrada

```php
public function mostrarEstacionesCliente($nombre, $pwd)
    {
        // if ($_SESSION['mostrarEstacionesCliente_nombre'] == $nombre && $_SESSION['mostrarEstacionesCliente_pwd'] == $pwd) {
        //     return $estacionesArr = $_SESSION['mostrarEstacionesCliente_result'];
        // }
        if ($this->conectar()) {
            $consulta = "SELECT estaciones.nombre_estacion, estaciones.id_estacion, estaciones.latitud, estaciones.longitud
            FROM usuarios INNER JOIN usuario_estacion ON usuarios.id_usuario = usuario_estacion.id_usuario
            INNER JOIN estaciones ON usuario_estacion.id_estacion = estaciones.id_estacion
            WHERE usuarios.nombre ='$nombre' AND usuarios.hash ='$pwd'";
            $resultado = pg_query($this->conexion, $consulta);
            if ($this->consultaExitosa($resultado)) {
                $estacionesArr = pg_fetch_all($resultado);
                // $_SESSION['mostrarEstacionesCliente_nombre'] = $nombre;
                // $_SESSION['mostrarEstacionesCliente_pwd'] = $pwd;
                // $_SESSION['mostrarEstacionesCliente_result'] = $estacionesArr;
                return $estacionesArr;
            } else {
                return false;
            }
        }
    }
```

### FUNCION **obtenerFotoEstacion()**

> @params INT id_usuario

> @returns Mixed String foto | Bool

Esta función pública devulve los datos de imagen cifrados de una estación dada su id. Se usa en la sección principal para los mapas y en las secciones de estación. Si esta estacion no tiene foto, devuelve false

```php
public function obtenerFotoEstacion($id_estacion)
    {
        if ($_SESSION['obtenerFotoEstacion_id_estacion'] == $id_estacion) {
            return $foto = $_SESSION['obtenerFotoEstacion_result'];
        }
        if ($this->conectar()) {
            $consulta = "SELECT foto as foto
            FROM estaciones
            WHERE id_estacion = " . $id_estacion;
            $resultado = pg_query($this->conexion, $consulta);
            if ($this->consultaExitosa($resultado)) {
                $foto = pg_fetch_all($resultado)[0]['foto'];
                $_SESSION['obtenerFotoEstacion_id_estacion'] = $id_estacion;
                $_SESSION['obtenerFotoEstacion_result'] = $foto;
                return $foto;
            } else {
                return false;
            }
        }
    }
```

### FUNCION **obtenerAlarmasUsuario()**

> @params INT id_usuario, String orden, String sentido, TS fechaInicio, TS fechaFin

> @returns Mixed Array | Bool

Esta función pública sirve para para extraer las alarmas de las estaciones pertenecientes a un usuario dado su id, sobre unas fechas dados sus TimeStamps en un orden y sentido dados en los parámetros. Se usa en la sección de alarmas.

```php
public function obtenerAlarmasUsuario($id_usuario, $orden, $sentido, $fechaInicio, $fechaFin)
    {
        if ($_SESSION['obtenerAlarmasUsuario_id'] == $id_usuario && $_SESSION['obtenerAlarmasUsuario_orden'] == $orden && $_SESSION['obtenerAlarmasUsuario_sentido'] == $sentido && $_SESSION['obtenerAlarmasUsuario_fechaini'] == $fechaInicio && $_SESSION['obtenerAlarmasUsuario_fechafin'] = $fechaFin) {
            return $alarmas = $_SESSION['obtenerAlarmasUsuario_alarmas'];
        }
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
            $conAlarmas = "SELECT DISTINCT
            estaciones.nombre_estacion, tags.nombre_tag, alarmas.id_alarmas, alarmas.valor_alarma, alarmas.fecha_origen, alarmas.fecha_restauracion, alarmas.estado, alarmas.ack_por, alarmas.fecha_ack
            FROM alarmas INNER JOIN estacion_tag ON alarmas.id_tag = estacion_tag.id_tag INNER JOIN usuario_estacion ON usuario_estacion.id_estacion = estacion_tag.id_estacion INNER JOIN estaciones ON estaciones.id_estacion = estacion_tag.id_estacion INNER JOIN tags ON alarmas.id_tag = tags.id_tag
            WHERE usuario_estacion.id_usuario = " . $id_usuario[0]['id_usuario'] . "";
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
            // else{
            //     $conAlarmas .= "AND alarmas.fecha_origen::date > current_date::date - interval '7 days'";
            // }
            if ($sentido != null) {
                if ($sentido == 'ASC') {
                    $conAlarmas .= " ORDER BY $prioridad ASC";
                } else {
                    $conAlarmas .= " ORDER BY $prioridad DESC";
                }
            } else {
                $conAlarmas .= " ORDER BY $prioridad DESC";
            }
            $conAlarmas .= " LIMIT 500";
            $resulAlarmas = pg_query($conAlarmas);
            if ($this->consultaExitosa($resulAlarmas)) {
                $alarmas = pg_fetch_all($resulAlarmas);
                $_SESSION['obtenerAlarmasUsuario_id'] = $id_usuario;
                $_SESSION['obtenerAlarmasUsuario_orden'] = $orden;
                $_SESSION['obtenerAlarmasUsuario_sentido'] = $sentido;
                $_SESSION['obtenerAlarmasUsuario_fechaini'] = $fechaInicio;
                $_SESSION['obtenerAlarmasUsuario_fechafin'] = $fechaFin;
                $_SESSION['obtenerAlarmasUsuario_alarmas'] = $alarmas;
                return $alarmas;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

```

### FUNCION **obtenerAlarmasEstacion()**

> @params INT id_estacion, String orden, String sentido, TS fechaInicio, TS fechaFin

> @returns Mixed Array | Bool

Esta función pública extrae las alarmas de una estacion dado su id, sobre unas fechas dados sus TimeStamps en un orden y sentido dados en los parámetros. Se usa en la sección de alarmas.

```php
public function obtenerAlarmasEstacion($id_estacion, $orden, $sentido, $fechaInicio, $fechaFin)
    {
        if ($_SESSION['obtenerAlarmasEstacion_id_estacion'] == $id_estacion && $_SESSION['obtenerAlarmasEstacion_orden'] == $orden && $_SESSION['obtenerAlarmasEstacion_sentido'] == $sentido && $_SESSION['obtenerAlarmasEstacion_fechaini'] == $fechaInicio && $_SESSION['obtenerAlarmasEstacion_fechafin'] = $fechaFin) {
            return $alarmasEstacion = $_SESSION['obtenerAlarmasEstacion_id_estacion'];
        }

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
                $_SESSION['obtenerAlarmasEstacion_id_estacion'] = $id_estacion;
                $_SESSION['obtenerAlarmasEstacion_orden'] = $orden;
                $_SESSION['obtenerAlarmasEstacion_sentido'] = $sentido;
                $_SESSION['obtenerAlarmasEstacion_fechafin'] = $fechaInicio;
                $_SESSION['obtenerAlarmasEstacion_fechafin'] = $fechaFin;
                $_SESSION['obtenerAlarmasEstacion_alarmas'] = $alarmasEstacion;
                return $alarmasEstacion;
            }
        } else {
            return false;
        }
    }
```

### FUNCION **obtenerDetallesAlarma()**

> @params INT id_alarma

> @returns Mixed Array | Bool

Esta función pública extrae los datos históricos del tag vinculado a una alarma dada su id, para mostrar durante un margen de un dia la tendencia de sus datos. Se usa en la sección de alarmas

```php
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
```

### FUNCION **datosEstacion()**

> @params INT id_estacion, Bool todos

> @returns Array

Esta función pública obtiene la última información conocida de una estación dado su id. Se usa en las secciones de estación.

```php
public function datosEstacion($id_estacion, $todos)
    {
        if ($_SESSION['datosEstacion_id_estacion'] == $id_estacion && $_SESSION['datosEstacion_todos'] == $todos) {
            return $ultimosDatosEstacionLimpio = $_SESSION['datosEstacion_result'];
        }

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
            $_SESSION['datosEstacion_id_estacion'] = $id_estacion;
            $_SESSION['datosEstacion_todos'] = $todos;
            $_SESSION['datosEstacion_result'] = $ultimosDatosEstacionLimpio;
            return $ultimosDatosEstacionLimpio;
        }
    }
```

### FUNCION **tagsEstacion()**

> @params INT id_estacion

> @returns Mixed Array | Bool

Esta función obtiene los tags historizables de una estación dado su id. Se usa en las gráficas y secciones de estación.

```php
public function tagsEstacion($id_estacion)
    {
        if ($this->conectar()) {
            $conTags = "SELECT tags.id_tag, tags.nombre_tag FROM estacion_tag INNER JOIN tags ON tags.id_tag = estacion_tag.id_tag WHERE estacion_tag.id_estacion = $id_estacion AND tags.historizar = true AND tags.nombre_tag NOT LIKE('%Bomba%')";
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
```

### FUNCION **todosTagsEstacion()**

> @params INT id_estacion

> @returns Mixed Array | Bool

Esta función obtiene los tags de una estación dado su id. Se usa en las gráficas, sección principal y secciones de estación.

```php
    public function todosTagsEstacion($id_estacion)
    {
        if ($_SESSION['todosTagsEstacion_id_estacion'] == $id_estacion) {
            return $tagsEstacion = $_SESSION['todosTagsEstacion_result'];
        }
        if ($this->conectar()) {
            $conTags = "SELECT tags.id_tag, tags.nombre_tag FROM estacion_tag INNER JOIN tags ON tags.id_tag = estacion_tag.id_tag WHERE estacion_tag.id_estacion = " . $id_estacion . " AND tags.nombre_tag NOT LIKE('%Alarma%');";
            $resulTags = pg_query($this->conexion, $conTags);
            if ($this->consultaExitosa($resulTags)) {
                $tagsEstacion = pg_fetch_all($resulTags);
                $_SESSION['tagsEstacion'] = $tagsEstacion;
                $_SESSION['todosTagsEstacion_id_estacion'] = $id_estacion;
                $_SESSION['todosTagsEstacion_result'] = $tagsEstacion;
                return $tagsEstacion;
            } else {
                return false;
            }
            return false;
        }
    }
```

### FUNCION **tagsAnalogHisto()**

> @params Array estaciones

> @returns Mixed Array | Bool

Esta función obtiene los tags analógicos historizables de un grupo de estaciones dados los id de las estaciones.

```php
public function tagsAnalogHisto($estaciones)
    {
        if ($_SESSION['tagsAnalogHisto_estaciones'] == $estaciones) {
            return $tagsAnalogsHisto = $_SESSION['tagsAnalogHisto_result'];
        }
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
            $_SESSION['tagsAnalogHisto_estaciones'] = $estaciones;
            $_SESSION['tagsAnalogHisto_result'] = $tagsAnalogsHisto;
            return $tagsAnalogsHisto;
        }
        return false;
    }
```

### FUNCION **historicosEstacion()**

> @params INT id_estacion, TS fechaIni, TS fechaFin

> @returns Mixed Array | Bool

Esta función obtiene los datos históricos de todos los tags historizables de una estación dado el id de la estación entre unas fechas comprendidas entre los TimeStamps de fechaIni y fechaFin.

```php
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
```

### FUNCION **historicosTagEstacion()**

> @params INT id_tag

> @returns Mixed Array | Bool

Esta función obtiene los datos_historicos de los ultimos 7 dias en un agregado 5 minutal de un tag dado su id. Se usa en graficas > vista rápida

```php
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
```

### FUNCION **historicosTagEstacionCustom()**

> @params INT id_estacion, INT id_tag, Array ajustesMeta, TS fechaInicio, TS fechaFin

> @returns Mixed Array | Bool

Esta fucnión pública obtiene los datos historicos en agregados 5 minutales de un tag dado su id entre unas fechas dados los TimeStamps con una configuración dada en ajustesMeta de una estacion dada su id.
Se usa en gráficas > vista personalizada.

```php
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
                    $ultVal = null;
                    foreach ($dato as $factor => $valor) {
                        if ($valor != null && $factor != 'ts') {
                            $datosHisto[$index]['valor'] = number_format($valor, 2);
                            $ultVal = number_format($valor, 2);
                        }
                        //rellena huecos vacios con el ultimo valor (proto)
                        if ($valor == null && $factor != "ts") {
                            $datosHisto[$index]['valor'] = $ultVal;
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
```

### FUNCION **reconocerAlarma()**

> @params INT id_alarma, String usuario, TS hora

> @returns Bool

Esta función pública se usa en alarmas y sirve para reconocer una alarma dada su id por un usuario dado su nombre a una hora dada su TS.

```php
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
```

### FUNCION **alarmasSur()**

> @params INT id_usuario

> @returns Mixed Array | Bool

Esta función pública se usa en el menú sur en las secciones distintas a las de estación.
Extrae las ultimas 7 alarmas de las estaciones que pertenezcan a un usuario dado su id.

```php
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
```

### FUNCION **alarmasEstacionSur()**

> @params INT id_estacion

> @returns Mixed Array | Bool

Esta función pública se usa en el menú sur en las secciones de estación.
Extrae las ultimas 7 alarmas de las estaciones que pertenezcan a una estación dada su id.

```php
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
```

### FUNCION **ultimaComunicacionEstacion()**

> @params INT id_estacion

> @returns String

Esta función pública obtiene la fecha de la última comunicación de una estación dada su id.
Se usa en la sección principal, estaciones y comunicaciones

```php
public function ultimaComunicacionEstacion($id_estacion)
    {
        if ($this->conectar()) {
            $consulta = "SELECT estaciones.id_estacion, estaciones.nombre_estacion, datos_valores.valor_date, tags.nombre_tag,estaciones.latitud,estaciones.longitud, estaciones.foto  FROM estaciones INNER JOIN estacion_tag ON estaciones.id_estacion = estacion_tag.id_estacion INNER JOIN tags ON tags.id_tag = estacion_tag.id_tag INNER JOIN datos_valores ON estacion_tag.id_tag = datos_valores.id_tag WHERE tags.nombre_tag LIKE 'Ultima Comunicacion%' AND estaciones.id_estacion = " . $id_estacion . " ORDER BY estaciones.nombre_estacion DESC";
            // $consulta = "SELECT estaciones.id_estacion, estaciones.nombre_estacion, datos_valores.valor_date, tags.nombre_tag,estaciones.latitud,estaciones.longitud, estaciones.foto  FROM estaciones INNER JOIN estacion_tag ON estaciones.id_estacion = estacion_tag.id_estacion INNER JOIN tags ON tags.id_tag = estacion_tag.id_tag INNER JOIN datos_valores ON estacion_tag.id_tag = datos_valores.id_tag WHERE tags.nombre_tag LIKE 'Ultima Comunicacion%' AND estaciones.id_estacion = " . $id_estacion . " ORDER BY datos_valores.fecha DESC";
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
```

### FUNCION **calidadTagsEstacion()** --> OBSOLETO

> @params INT id_estacion

> @returns Array

Esta función está obsoleta y ya no se usa.

### FUNCION **obtenerNombreEstacion()**

> @params INT id_estacion

> @returns Mixed String | Bool

Esta función pública obtiene el nombre de una estación dada su id.

```php
public function obtenerNombreEstacion($id_estacion)
    {
        if ($id_estacion == $_SESSION['obtenerNombreEstacion_id_estacion']) {
            return $estacion = $_SESSION['obtenerNombreEstacion_result'];
        }

        if ($this->conectar()) {
            $consulta = "SELECT nombre_estacion FROM estaciones WHERE id_estacion = " . $id_estacion;
            $resul = pg_query($consulta);
            if ($this->consultaExitosa($resul)) {
                $estacion = pg_fetch_all($resul);
                $_SESSION['obtenerNombreEstacion_id_estacion'] = $id_estacion;
                $_SESSION['obtenerNombreEstacion_result'] = $estacion;
                return $estacion;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
```

### FUNCION **metaTag()**

> @params INT id_tag, INT id_estacion

> @returns Mixed Array | Bool

Esta función pública obtiene los metadatos calculados de un tag dado su id de una estacion dada su id. Se usa en ambas vistas de gráficas.

```php
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
```

### FUNCION **tagTrend()** --> OBSOLETO

> @params INT id_tag, INT id_estacion

> @returns Mixed Array | Bool

Esta función pública obtiene un trend (7 maximos valores de 7 días) de un tag dado su id de una estacion dada su id.
Esta función es obsoleta y ya no se usa por motivos de optimización. En su lugar se usa **tagsTrends()**.

### FUNCION **tagsTrends()**

> @params Array datosAnalog

> @returns Mixed Array | Bool

Esta fucnión pública obtiene los trends (7 maximos valores de 7 días) de varios tags dados sus ids de una estacion dada su id. Se usa en las secciones de estación.
Sustituye a **tagTrend()**.

```php
public function tagsTrends($datosAnalog)
    {
        $conTrends = "";
        $conAux = "";
        if ($this->conectar()) {
            $conAux = "(";
            $a = 0;
            foreach ($datosAnalog as $indexTag => $datosTag) {
                if ($indexTag != null && $datosTag != null) {
                    $tag = $datosTag->id_tag;
                    if ($a == 0) {
                        $conAux .= " $tag ";
                        $a++;
                    } else {
                        $conAux .= " ,$tag ";
                    }
                }
            }
            $conAux .= ")";
            $conTrends = "SELECT datos_historicos.id_tag, MAX(datos_historicos.valor_acu) as acu, MAX(datos_historicos.valor_int) as int, MAX(datos_historicos.valor_float) as float, datos_historicos.fecha::date
            from datos_historicos inner join estacion_tag on datos_historicos.id_tag = estacion_tag.id_tag
            where datos_historicos.fecha::date > current_date::date - interval '7 days' AND datos_historicos.id_tag IN $conAux GROUP BY datos_historicos.id_tag, datos_historicos.fecha::date";

            $resTrends = pg_query($this->conexion, $conTrends);
            if ($this->consultaExitosa($resTrends)) {
                $datosTrendsTags = pg_fetch_all($resTrends);
                return $datosTrendsTags;
            }
            return $conTrends;
        }
        return false;
    }
```

### FUNCION **informeSeñalEstacion()**

> @params INT, id_estacion, String señal, TS fechaIni, TS fechaFin

> @returns Array

Esta función pública obtiene los informes dado su tipo, de los tags de una estacion dado su id comprendido entre unas fechas definidas sus TS.

```php
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
```

### FUNCION **feedPrincipalDigital()** --> en Desuso

> @params Array estaciones

> @returns Mixed Array | Bool

Está función pública devolvía las 4 alertas de tags digitales mas recientes pertenecientes a un grupo de estaciones dados sus id.
Su utilidad era tan escasa que se eliminó el feed digital de la sección de inicio dejando en desuso esta función.

```php
public function feedPrincipalDigital($estaciones)
    {
        if ($_SESSION['feedPrincipalDigital_estaciones'] == $estaciones) {
            return $feed = $_SESSION['feedPrincipalDigital_result'];
        }
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
                        // CRITERIO ORIGINAL
                        // $conAlarma = "SELECT fecha_origen, id_tag, valor_alarma
                        // FROM alarmas
                        // WHERE id_tag = " . $id . " AND estado IN(1,3) AND fecha_origen::date > current_date::date - interval '3 days'
                        // AND NOT valor_alarma = ''
                        // ORDER BY fecha_origen DESC";
                        //WHERE estado IN(1,3) AND id_tag = " . $id . " AND fecha_origen::date > current_date::date - interval '3 days'

                        //CRITERIO NUEVO
                        $conAlarma = "SELECT fecha_origen, id_tag, valor_alarma
                        FROM alarmas
                        WHERE id_tag = " . $id . " AND estado IN(1,3) AND fecha_origen::date > current_date::date - interval '7 days'
                        ORDER BY fecha_origen DESC";

                        $resAlarmas = pg_query($this->conexion, $conAlarma);
                        if ($this->consultaExitosa($resAlarmas)) {
                            $alarmasTagDigi = pg_fetch_all($resAlarmas);
                            $alarmasTagDigi[0]['nombre'] = $tag['nombre_tag'];
                            $feed[$estacion['nombre_estacion']][$id] = $alarmasTagDigi[0];
                            $_SESSION['feedPrincipalDigital_estaciones'] = $estaciones;
                            $_SESSION['feedPrincipalDigital_result'] = $feed;
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
```

### FUNCION **confirmarWidget()**

> @params String wid, INT tag, INT id_usuario

> @returns Bool

Esta función pública pertenece a los ajustes de la sección principal y establece un feed analógico para un widget concreto.

```php
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
```

### FUNCION **feedPrincipalCustom()**

> @params INT id_usuario

> @returns Array

Esta función pública se usa en la sección principal y extrae los feeds de los tags definidos en la configuracion de un usuario concreto según su id.

```php
 public function feedPrincipalCustom($id_usuario)
    {
        if ($id_usuario == $_SESSION['feedPrincipalCustom_id_usuario']) {
            return $infoTag = $_SESSION['feedPrincipalCustom_result'];
        }
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
                    //$conTrendDia = "SELECT MAX(datos_historicos.valor_acu),MAX(datos_historicos.valor_float),MAX(datos_historicos.valor_int),datos_historicos.fecha::date FROM datos_historicos WHERE datos_historicos.id_tag =".$tag. " AND datos_historicos.fecha::date > current_date::date - interval '7 days' GROUP BY datos_historicos.fecha::date LIMIT 7";
                } else {
                    //trend original
                    //$conTrendDia = "SELECT datos_historicos.fecha::time, datos_historicos.valor_acu, datos_historicos.valor_float, valor_int FROM datos_historicos WHERE id_tag=" . $tag . " AND datos_historicos.fecha::date >= current_date::date - interval '1 days' AND datos_historicos.fecha::date <= current_date::date ORDER BY fecha desc";
                    //trend modificada
                    $conTrendDia = "WITH t as (SELECT to_timestamp(round((extract(epoch from fecha)) / 10) * 10)::TIMESTAMP AS fecha, AVG(valor_float) as valor_float, AVG(valor_acu) as valor_acu, AVG(valor_int) as valor_int FROM datos_historicos WHERE id_tag = " . $tag . " AND datos_historicos.fecha::date >= current_date::date - interval '1 days' AND datos_historicos.fecha::date <= current_date::date GROUP BY fecha),contiguous_ts_list as (select fecha from generate_series((select min(fecha) from t),(select max(fecha) from t),interval '1 hour') fecha) SELECT * from contiguous_ts_list left outer join t using(fecha) ORDER BY fecha DESC";
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
            $_SESSION['feedPrincipalCustom_id_usuario'] = $id_usuario;
            $_SESSION['feedPrincipalCustom_result'] = $infoTag;
            return $infoTag;
        }
    }
```

### FUNCION **borrarPreset()**

> @params String n_preset, INT id_usuario

> @returns Bool

Función pública perteneciente a gráficas > vista personalizada que borra un preset dado su nombre guardado en la config de un usuario dado su id.

```php
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
```

### FUNCION **leerPresets()**

> @params INT id_usuario

> @returns Mixed Array | Bool

Función publica perteneciente a la sección de gráficas > vista personalizada que busca los presets guardados en la configuración de un usuario dado su id.

```php
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
```

### FUNCION **guardarPreset()**

> @params String usuario, String nombre, String estacion, Array tags_colores

> @returns Bool

Función publica perteneciente a la sección de gráficas > vista personalizada que guarda un preset según su nombre y los tags incluidos con sus colores a un usuario dado su id.

```php
public function guardarPreset($usuario, $nombre, $estacion, $tags_colores)
    {
        $codigo = $nombre . "@" . $estacion . "?";
        foreach ($tags_colores as $tag => $color) {
            if ($color != null) {
                $codigo .= "/" . $tag . ":" . $color . "";
            }
        }
        $id_usuario = $this->obtenerIdUsuario($usuario);
        if ($id_usuario) {
            $secu = "INSERT INTO graficas(id_usuario, configuracion)
            VALUES (" . $id_usuario[0]['id_usuario'] . ", '" . $codigo . "')";
            pg_query($this->conexion, $secu);
            return true;
        }
        return false;
    }
```

---

## **2.2 CLASE DataWit.php**

> importante: esta clase se apoya en los drivers de SQLServer para PHP (sqlsrv) no incluidos en apache.

> Los contenidos de esta clase son provisionales y están sujetos a posibles cambios.

Esta clase contiene las funciones para acceder a las tablas en bases de datos de SQL Server, dónde se encuentran las configuraciones, datos y funciones de las consignas y los plannings de las señales provenientes de WIT. Al no compartir la arquitectura con Aquando_ddbb, también tiene una conexión auxiliar a otra base de datos con la información necesaria para relacionar la información de Aquando con la del entorno WIT.

La clase instancia al principio las variables y constantes necesarias para establecer conexiones al servidor de SQLServer. La función de constructor se encuentra vacía y la clase no requiere de ningún parámetro.

### FUNCION **conectar()**

> @params Void

> @returns Mixed Sqlstmt | Bool

Función privada que conecta con _DBEASY452_. Se apoya en las constantes de conexión definidas en la clase. Esta base de datos es la que tiene la información útil de las consignas.

```php
private function conectar()
    {
        if (!$this->conexion) {
            $this->info_server = array("Database" => "DBEASY452", "Uid" => "sa", "PWD" => "dateando", "Encrypt" => false);
            $stmt = sqlsrv_connect($this->nombre_server, $this->info_server);
            if ($this->consultaExitosa($stmt)) {
                return $this->conexion = $stmt;
            } else {
                return false;
            }
        }
        return $this->conexion;
    }
```

### FUNCION **conectarAux()**

> @params Void

> @returns Mixed Sqlstmt | Bool

Función privada que establece una conexión auxiliar con _Conversion_Aquando_. Está base de datos tiene las relaciones entre el entorno de Aquando y el de WIT

```php
private function conectarAux()
    {
        if (!$this->conexionAux) {
            $this->info_server = array("Database" => "Conversion_Aquando", "Uid" => "sa", "PWD" => "dateando", "Encrypt" => false);
            $stmt = sqlsrv_connect($this->nombre_server, $this->info_server);
            if ($this->consultaExitosa($stmt)) {
                return $this->conexionAux = $stmt;
            } else {
                return false;
            }
        }
        return $this->conexionAux;
    }
```

### FUNCION **consultaExitosa()**

> @params Sqlstmt

> @returns Bool

Función privada que comprueba los resultados de una consulta.

```php
private function consultaExitosa($stmt)
    {
        if ($stmt) {
            return true;
        } else {
            print_r(sqlsrv_errors()); //provisional
            return false;
        }
    }
```

### FUNCION **consignasEstacion()**

> @params String estacion

> @returns Mixed Array | Bool

Función pública que obtinene las consignas que existen en una estación dada su nombre.
Se usa en las secciones de estacion > ajustes

```php
    public function consignasEstacion($estacion)
    {
        if ($this->conectarAux() && $estacion != "Deposito Berroa") {
            $consulta = "SELECT * FROM Info_lkv where estacion like('%" . $estacion . "%') AND nombre_tag like ('%Consigna%') AND Nombre_variable_wit LIKE('%Import%')";
            // $params = array($estacion);
            $respuesta = sqlsrv_query($this->conexionAux, $consulta);
            if ($this->consultaExitosa($respuesta)) {
                $datos = array();
                while ($fila = sqlsrv_fetch_array($respuesta, SQLSRV_FETCH_ASSOC)) {
                    $datos[] = $fila;
                }
                sqlsrv_free_stmt($respuesta);
                return $datos;
            }
        }
        return false;
    }
```

### FUNCION **leerConsignaWit()**

> @params String recurso

> @returns Mixed Array | Bool

Función pública que obtiene los valores de una consigna determinada dada su identificación dentro del entorno WIT.
Se usa en las secciones de estacion > ajustes

```php
    public function leerConsignaWIT($recurso)
    {
        if ($this->conectar()) {
            $consulta = "SELECT * FROM [DBEASY452].[dbo].[WValue] WHERE ValueWOSAdd LIKE('%" . $recurso . "%') AND ValueWOSAdd LIKE('%InLink%')";
            // return $consulta;
            $respuesta = sqlsrv_query($this->conexion, $consulta);
            if ($this->consultaExitosa($respuesta)) {
                $datos = sqlsrv_fetch_array($respuesta, SQLSRV_FETCH_ASSOC);
                sqlsrv_free_stmt($respuesta);
                return $datos;
            }
        }
        return false;
    }
```

### FUNCION **modificarConsignaWit()**

> @params String ref, Float valor

> @returns String

Función pública que modifica el valor de una consigna WIT dada su referencia dentro del entorno WIT por un valor dado en los parámetros.

```php
    public function modificarConsignaWit($ref, $valor) //habra que meter params (estacion, tag, consigna, valor etc)
    {
        if ($this->conectar()) {
            $conConsignas = "UPDATE [DBEASY452].[dbo].[WValue] SET ValueReadData = '" . $valor . "', ValueWriteStatus = 10 WHERE ValueWOSAdd LIKE('%" . $ref . "InLink%')";
            $params = array();
            $stmt = sqlsrv_query($this->conexion, $conConsignas, $params);
            if ($this->consultaExitosa($stmt)) {
                sqlsrv_free_stmt($stmt);
                return 'updated';
            }
        }
        return 'error';
    }
```

### FUNCION **leerPlanningsEstacion()** --> WIP

> @params ?

> @returns ?

_Está función aun no está implementada y su código no es definitivo._
Esta función pública se encarga de listar todos los plannnings existentes de una estación determinada.

```php
//Código no disponible
```

### FUNCION **leerValorPlanning()** --> WIP

> @params ?

> @returns ?

_Está función aun no está implementada y su código no es definitivo._
Esta función pública se encarga de leer el valor de un planning dada su referencia.

```php
//Código no disponible
```

### FUNCION **modificarPlanning()** --> WIP

> @params ?

> @returns ?

_Está función aun no está implementada y su código no es definitivo._
Esta función pública se encarga de modificar el valor de un planning determinado por una configuración nueva determinada.

```php
//Código no disponible
```

---

## **3. MODELOS**

Los modelos son clases de PHP situadas bajo el directorio _App/Models_ sobre las cuales se apoyan tanto el controlador, como los archivos en Ajax para funcionar. Estas incluyen funciones especiales y conforman los objetos con los que se trabaja en Aquando.
Son tres:

- Contras.php: la clase que trabaja las contraseñas y cifrados.
- Usuario.php: la clase que trabaja los objetos usuario y las sesiones.
- Validador.php: la clase que proteje el back-end de imputs erróneos o maliciosos.

## **3.1 CLASE Contras.php**

Esta clase se encarga de todo lo relacionado con contraseñas y cifrados de los usuarios.
Al necesitar hacer peticiones que en ocasiones mandan información comprometida, la aplicación se encarga de cifrarla en función de unas claves únicas en cada sesión, para cada usuario que además cambian con el tiempo.

> El único sentido de esta clase es la mantener la seguridad de las contraseñas y que en caso de que alguien las robe, no sirvan de nada.

La clase comienza instanciando las constantes y variables en las que se apoyarán el resto de fucniones. Estas son:

- HASH
- COST
- uData
- db
- hash

El constructor también crea un objeto _Database_ y pide un id de usuario sobre el cual empezar a trabajar.

### FUNCION **save()**

> @params Void

> @returns Void

Fucnion pública que se encarga de guardar datos de usuario apoyandose en otras funciones de la clase.

```php
public function save()
    {
        $this->db->updateUserData($this->uData[0]['id_usuario'], $this->uData[0]['id_usuario']['password']);
    }
```

### FUNCION **loginUsuario()**

> @params String password

> @returns Bool

Función pública que se usa en el controlador en la parte de log-in. Compara el hash del usuario definido en las constantes con una contraseña dada en los parametros cifrandola según debería.

```php
 public function loginUsuario($password)
    {
        if (password_verify($password, $this->uData[0]['hash'])) {
            $this->hash = $this->uData[0]['hash'];
            if (password_needs_rehash($this->uData[0]['hash'], self::HASH, ['cost' => self::COST])) {
                $this->setPassword($password);
                $this->save();
            }
            //volver a controlador-->exito
            return true;
        }
        //volver a controlador-->error
        return false;
    }
```

### FUNCION **setPassword()**

> @params String password

> @returns Void

Función pública que cifra la contraseña en uData según los parámetros preestablecidos.

```php
public function setPassword($password)
    {
        $this->uData[0]['hash'] = password_hash($password, self::HASH, ['cost' => self::COST]);
    }
```

### FUNCION **hashear()**

> @params String pwd

> @returns String

Función pública que se usa como debug. Devuelve el hash de cualquier texto.

```php
public function hashear($pwd)
    {
        return password_hash($pwd, self::HASH, ['cost' => self::COST]);
    }
```

### FUNCION **getHash()**

> @params Void

> @returns String

Función getter estándar de la propiedad _hash_ de la clase.

## **3.2 CLASE Usuario.php**

Esta es la clase que define el objeto usuario usado en el controlador y en los archivos Ajax.
Contiene las distintas acciones que puede hacer un usuario en Aquando, aunque no todas.

> depende de Database.php

La clase al instanciarse importa la clase _Database.php_ y define las propiedades del usuario.
Estas son:

- nombre
- contrasena
- cliente (ya no se usa)
- estacionesUsuario
- DB

En su constructor se solicitan por lo menos el nombre y la contrasena para instanciar el objeto _Usuario_. También instancia el objeto Database.

--> funciones

## **3.3 CLASE Validador.php**

Resumen

> aclaraciones

--> Primeros pasos, constantes y propiedades

--> Constructor

--> funciones

---
