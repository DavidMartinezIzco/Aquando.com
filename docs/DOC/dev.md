<div style='padding:1em;border:3px solid gray;border-radius:1em;'>

# DOCUMENTACIÓN DE AQUANDO PARA DESARROLLADORES

## Manual básico de desarrollador para <span style='color:darkblue;'><b>Aquando</b></span>

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

# GENERAL

### Información básica:

> <span style='color:darkblue;'><b>Aquando</b></span> es una aplicación API-REST escrita mayormente en <span style='color:purple'><b>PHP</b></span> y <span style='color:gold'><b>JS</b></span>.
> Se apoya en la plataforma CodeIgniter para organizarla en un estándar de producción común.

Básicamente, <span style='color:darkblue;'><b>Aquando</b></span> consiste en un back-end que va a escuchar las distintas peticiones que se realicen desde cliente. Dependiendo de estas, desde el controlador se decide que hacer y como.
Dependiendo del tipo de petición en ocasiones no será el controlador lo que procese las peticiones sino archivos en <span style='color:purple'><b>PHP</b></span> también que se comunican con el cliente a traves de <span style='color:red'><b>AJAX</b></span>. Estos archivos existen dedicados para cada sección y realizan acciones sencillas en tiempo real funcionando como pequeños controladores para las secciones.
El resto de acciones (normamente gráficas y estéticas) son realizadas desde el lado cliente con JavaScript siempre que no requieran de archivos del servidor ni de las bases de datos.

### Dependencias:

**SERVIDOR**

- Apache2 en entorno Linux o XAMPP en Windows.
- Extensiones para <span style='color:purple'><b>PHP</b></span> 7+
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

# **1. CONTROLADOR**

### **CLASE Inicio.php**

El controlador se llama Inicio.php y se ejecuta despues de BaseController.php y el resto de componentes de codeIgniter.

El controlador es el componente que gestiona las peticiones que llegan desde lado cliente. Pero también se encarga de instanciar la aplicación por primera vez cuando esta empieza a funcionar.

Para funcionar primero implementa las clases sobre las que se apoya para procesar la actividad en el servidor. Estas clases se explican mas en detalle en sus secciones mas adelante. Las clases son Usuario.php. Contras.php y Validador.php que se encuentran bajo la carpeta Models.
También en el contrusctor de clase, instancian losetos usuario, sesion y vlr (validador).

### FUNCIÓN **index()**

> <span style='color:green'>@returns</span> View

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

### FUNCIÓN **inicioSesion()**

> <span style='color:green'>@returns</span> View

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
                    if((!$this->vlr->valLog($nombre)) || !($this->vlr->valLog($pwd)) ){
                        echo '<script language="javascript">alert("carácteres no válidos")</script>';
                                return view('inicioSesion');
                    }
                    $this->usuario = new Usuario($nombre, $pwd);
                    if ($this->usuario->existeUsuario() == true) {
                        $id_usu = $this->usuario->obtenerIdUsuario($nombre);
                        if ($id_usu != null) {
                            $conSys = new Contras($id_usu);
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

### FUNCIÓN **estacion()**

> <span style='color:green'>@returns</span> View

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

### FUNCIÓN **graficas()**

> <span style='color:green'>@returns</span> View

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

### FUNCIÓN **alarmas()**

> <span style='color:green'>@returns</span> View

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
                $estaciones = $this->usuario->obtenerEstacionesUsuario($_SESSION['hpwd']);
                $datos['estaciones'] = $estaciones;
            }
            return view('alarmas', $datos);
        } else {
            return view('inicio');
        }
    }

```

### FUNCIÓN **informes()**

> <span style='color:green'>@returns</span> View

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

### FUNCIÓN **comunicaciones()**

> <span style='color:green'>@returns</span> View

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

# **2. BASES DE DATOS**

**IMPORTANTE**: Estas clases son provisionales antes de implementar otras tecnologías mas potentes como Apache Spark o Fiware.
El sistema de bases de datos se compone por dos clases: Database y DataWit.
Database es la mas grande de las dos y se usa para prácticamente todo. En ella se encuentran las interaciones con PostgreSQL.
DataWit es una clase que conecta con dos bases de datos en SQL Server para ver y editar consignas asi como plannings de tiempo para algunas estaciones que por limitaciones en las comunicaciones no se podía hacer desde PostgreSQL

## **2.1 CLASE Database.php**

> importante: esta clase se apoya en los drivers de PostgreSQL para <span style='color:purple'><b>PHP</b></span> (pgsql) no incluidos en apache

Por lo general en Database vamos a hacer consultas a PostgreSQL en función de unos parámetros incluidos en las llamadas a esta clase. Estas llamadas tienen varios origenes incluyendo el Controlador y los archivos de <span style='color:red'><b>AJAX</b></span>

Nada mas instanciar la clase, se inician unas constantes con las credenciales y configuración de conexión a la base de datos.
En el constructor tambien se instancia una funcion que en versiones mas recientes de <span style='color:purple'><b>PHP</b></span> ya está incluida pero que por limitaciones de Debian, al tener que usar PHP5 necesitamos.

### FUNCIÓN **conectar()**

> <span style='color:green'>@returns</span> Mixed: conexion | bool

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

### FUNCIÓN **consultaExitosa()**

> <span style='color:green'>@params</span> SQLSTMT resultado

> <span style='color:green'>@returns</span> bool

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

### FUNCIÓN **obtenerNombreTag()**

> <span style='color:green'>@params</span> <b>INT</b> id_tag

> <span style='color:green'>@returns</span> Mixed <b>String</b> | <b>Bool</b>

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

### FUNCIÓN **obtenerConsignasTag()**

> <span style='color:green'>@params</span> <b>INT</b> id_tag

> <span style='color:green'>@returns</span> Mixed <b>Array</b> | <b>Bool</b>

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

### FUNCIÓN **obtenerIdUsuario()**

> <span style='color:green'>@params</span> <b>String</b> nombre_usuario

> <span style='color:green'>@returns</span> Mixed <b>INT</b> | <b>Bool</b>

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

### FUNCIÓN obtenerClienteUsuario()

> <span style='color:green'>@params</span> <b>String</b> nombre_usuario

> <span style='color:green'>@returns</span> Mixed <b>String</b> | <b>Bool</b>

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

### FUNCIÓN **existeUsusario()**

> <span style='color:green'>@params</span> <b>String</b> nombre

> <span style='color:green'>@returns</span> <b>Bool</b>

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

### FUNCIÓN **userData()**

> <span style='color:green'>@params</span> <b>INT</b> id_usu

> <span style='color:green'>@returns</span> Mixed <b>Array</b> | <b>Bool</b>

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

### FUNCIÓN **updateUserData()**

> <span style='color:green'>@params</span> <b>INT</b> id_usu, <b>String</b> pwd

> <span style='color:green'>@returns</span> <b>Bool</b>

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

### FUNCIÓN **mostrarEstacionesCliente()**

> <span style='color:green'>@params</span> <b>String</b> nombre, <b>String</b> pwd

> <span style='color:green'>@returns</span> Mixed <b>Array</b> | <b>Bool</b>

Esta función pública recoge toda la información de las propiedades de todas las estaciones que pertenezcan a un usuario dado su nombre y contraseña cifrada

```php
public function mostrarEstacionesCliente($nombre, $pwd)
    {
        if ($this->conectar()) {
            $consulta = "SELECT estaciones.nombre_estacion, estaciones.id_estacion, estaciones.latitud, estaciones.longitud
            FROM usuarios INNER JOIN usuario_estacion ON usuarios.id_usuario = usuario_estacion.id_usuario
            INNER JOIN estaciones ON usuario_estacion.id_estacion = estaciones.id_estacion
            WHERE usuarios.nombre ='$nombre' AND usuarios.hash ='$pwd'";
            $resultado = pg_query($this->conexion, $consulta);
            if ($this->consultaExitosa($resultado)) {
                $estacionesArr = pg_fetch_all($resultado);
                return $estacionesArr;
            } else {
                return false;
            }
        }
    }
```

### FUNCIÓN **obtenerFotoEstacion()**

> <span style='color:green'>@params</span> <b>INT</b> id_usuario

> <span style='color:green'>@returns</span> Mixed <b>String</b> foto | <b>Bool</b>

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

### FUNCIÓN **obtenerAlarmasUsuario()**

> <span style='color:green'>@params</span> <b>INT</b> id_usuario, <b>String</b> orden, <b>String</b> sentido, <b>TS</b> fechaInicio, <b>TS</b> fechaFin

> <span style='color:green'>@returns</span> Mixed <b>Array</b> | <b>Bool</b>

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

### FUNCIÓN **obtenerAlarmasEstacion()**

> <span style='color:green'>@params</span> <b>INT</b> id_estacion, <b>String</b> orden, <b>String</b> sentido, <b>TS</b> fechaInicio, <b>TS</b> fechaFin

> <span style='color:green'>@returns</span> Mixed <b>Array</b> | <b>Bool</b>

Esta función pública extrae las alarmas de una estacion dado su id, sobre unas fechas dados sus TimeStamps en un orden y sentido dados en los parámetros. Se usa en la sección de alarmas.

```php
public function obtenerAlarmasEstacion($id_estacion, $orden, $sentido, $fechaInicio, $fechaFin)
    {
        if ($_SESSION['obtenerAlarmasEstacion_id_estacion'] == $id_estacion && $_SESSION['obtenerAlarmasEstacion_orden'] == $orden && $_SESSION['obtenerAlarmasEstacion_sentido'] == $sentido && $_SESSION['obtenerAlarmasEstacion_fechaini'] == $fechaInicio && $_SESSION['obtenerAlarmasEstacion_fechafin'] = $fechaFin) {
            return $alarmasEstacion = $_SESSION['obtenerAlarmasEstacion_id_estacion'];
        }
        if ($fechaInicio != null) {
        }
        if ($fechaFin != null) {
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

### FUNCIÓN **obtenerDetallesAlarma()**

> <span style='color:green'>@params</span> <b>INT</b> id_alarma

> <span style='color:green'>@returns</span> Mixed <b>Array</b> | <b>Bool</b>

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

### FUNCIÓN **datosEstacion()**

> <span style='color:green'>@params</span> <b>INT</b> id_estacion, <b>Bool todos</b>

> <span style='color:green'>@returns</span> <b>Array</b>

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

### FUNCIÓN **tagsEstacion()**

> <span style='color:green'>@params</span> <b>INT</b> id_estacion

> <span style='color:green'>@returns</span> Mixed <b>Array</b> | <b>Bool</b>

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

### FUNCIÓN **todosTagsEstacion()**

> <span style='color:green'>@params</span> <b>INT</b> id_estacion

> <span style='color:green'>@returns</span> Mixed <b>Array</b> | <b>Bool</b>

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

### FUNCIÓN **tagsAnalogHisto()**

> <span style='color:green'>@params</span> <b>Array</b> estaciones

> <span style='color:green'>@returns</span> Mixed <b>Array</b> | <b>Bool</b>

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

### FUNCIÓN **historicosEstacion()**

> <span style='color:green'>@params</span> <b>INT</b> id_estacion, <b>TS</b> fechaIni, <b>TS</b> fechaFin

> <span style='color:green'>@returns</span> Mixed <b>Array</b> | <b>Bool</b>

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

### FUNCIÓN **historicosTagEstacion()**

> <span style='color:green'>@params</span> <b>INT</b> id_tag

> <span style='color:green'>@returns</span> Mixed <b>Array</b> | <b>Bool</b>

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

### FUNCIÓN **historicosTagEstacionCustom()**

> <span style='color:green'>@params</span> <b>INT</b> id_estacion, <b>INT</b> id_tag, <b>Array</b> ajustesMeta, <b>TS</b> fechaInicio, <b>TS</b> fechaFin

> <span style='color:green'>@returns</span> Mixed <b>Array</b> | <b>Bool</b>

Esta fucnión pública obtiene los datos historicos en agregados 5 minutales de un tag dado su id entre unas fechas dados los TimeStamps con una configuración dada en ajustesMeta de una estacion dada su id.
Se usa en gráficas > vista personalizada.

```php
{
        if ($this->conectar()) {
            $seriesTagCustom = array();
            $metaCustom = array();
            $meta = $this->metaTag($id_tag, $id_estacion);
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
            $ini = strtotime($fechaInicio);
            $fin = strtotime($fechaFin);
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
                        if ($valor == null && $factor != "ts") {
                            $datosHisto[$index]['valor'] = $ultVal;
                        }
                        if ($factor == 'ts') {
                            $datosHisto[$index]['fecha'] = $valor;
                        }
                    }
                }
                $seriesTagCustom['tag'] = $datosHisto;
            } else {
                return false;
            }
        } else {
            return false;
        }
        return $seriesTagCustom;
    }
```

### FUNCIÓN **reconocerAlarma()**

> <span style='color:green'>@params</span> <b>INT</b> id_alarma, <b>String</b> usuario, <b>TS</b> hora

> <span style='color:green'>@returns</span> <b>Bool</b>

Esta función pública se usa en alarmas y sirve para reconocer una alarma dada su id por un usuario dado su nombre a una hora dada su <b>TS</b>.

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

### FUNCIÓN **alarmasSur()**

> <span style='color:green'>@params</span> <b>INT</b> id_usuario

> <span style='color:green'>@returns</span> Mixed <b>Array</b> | <b>Bool</b>

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

### FUNCIÓN **alarmasEstacionSur()**

> <span style='color:green'>@params</span> <b>INT</b> id_estacion

> <span style='color:green'>@returns</span> Mixed <b>Array</b> | <b>Bool</b>

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

### FUNCIÓN **ultimaComunicacionEstacion()**

> <span style='color:green'>@params</span> <b>INT</b> id_estacion

> <span style='color:green'>@returns</span> <b>String</b>

Esta función pública obtiene la fecha de la última comunicación de una estación dada su id.
Se usa en la sección principal, estaciones y comunicaciones

```php
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
```

### FUNCIÓN **calidadTagsEstacion()** --> OBSOLETO

> <span style='color:green'>@params</span> <b>INT</b> id_estacion

> <span style='color:green'>@returns</span> <b>Array</b>

Esta función está obsoleta y ya no se usa.

### FUNCIÓN **obtenerNombreEstacion()**

> <span style='color:green'>@params</span> <b>INT</b> id_estacion

> <span style='color:green'>@returns</span> Mixed <b>String</b> | <b>Bool</b>

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

### FUNCIÓN **metaTag()**

> <span style='color:green'>@params</span> <b>INT</b> id_tag, <b>INT</b> id_estacion

> <span style='color:green'>@returns</span> Mixed <b>Array</b> | <b>Bool</b>

Esta función pública obtiene los metadatos calculados de un tag dado su id de una estacion dada su id. Se usa en ambas vistas de gráficas.

```php
    public function metaTag($id_tag, $id_estacion)
    {
        if ($this->conectar()) {
            $metaDatos = array();
            $conmaxval = "SELECT MAX(datos_historicos.valor_int), CAST(MAX(datos_historicos.valor_float)*100 AS <b>INT</b>), MAX(datos_historicos.valor_acu) FROM datos_historicos INNER JOIN estacion_tag ON estacion_tag.id_tag = datos_historicos.id_tag WHERE datos_historicos.id_tag = " . $id_tag . " AND estacion_tag.id_estacion = " . $id_estacion . "";
            $conminval = "SELECT MIN(datos_historicos.valor_int),CAST(MIN(datos_historicos.valor_float)*100 AS <b>INT</b>), MIN(datos_historicos.valor_acu) FROM datos_historicos INNER JOIN estacion_tag ON estacion_tag.id_tag = datos_historicos.id_tag WHERE datos_historicos.id_tag = " . $id_tag . " AND estacion_tag.id_estacion = " . $id_estacion . "";
            $conmedia = "SELECT AVG(datos_historicos.valor_int),CAST(AVG(datos_historicos.valor_float)*100 AS <b>INT</b>), AVG(datos_historicos.valor_acu) FROM datos_historicos INNER JOIN estacion_tag ON estacion_tag.id_tag = datos_historicos.id_tag WHERE datos_historicos.id_tag = " . $id_tag . " AND estacion_tag.id_estacion = " . $id_estacion . "";
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

### FUNCIÓN **tagTrend()** --> OBSOLETO

> <span style='color:green'>@params</span> <b>INT</b> id_tag, <b>INT</b> id_estacion

> <span style='color:green'>@returns</span> Mixed <b>Array</b> | <b>Bool</b>

Esta función pública obtiene un trend (7 maximos valores de 7 días) de un tag dado su id de una estacion dada su id.
Esta función es obsoleta y ya no se usa por motivos de optimización. En su lugar se usa **tagsTrends()**.

### FUNCIÓN **tagsTrends()**

> <span style='color:green'>@params</span> <b>Array</b> datosAnalog

> <span style='color:green'>@returns</span> Mixed <b>Array</b> | <b>Bool</b>

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

### FUNCIÓN **informeSeñalEstacion()**

> <span style='color:green'>@params</span> <b>INT</b>, id_estacion, <b>String</b> señal, <b>TS</b> fechaIni, <b>TS</b> fechaFin

> <span style='color:green'>@returns</span> <b>Array</b>

Esta función pública obtiene los informes dado su tipo, de los tags de una estacion dado su id comprendido entre unas fechas definidas sus <b>TS</b>.

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

### FUNCIÓN **feedPrincipalDigital()** --> en Desuso

> <span style='color:green'>@params</span> <b>Array</b> estaciones

> <span style='color:green'>@returns</span> Mixed <b>Array</b> | <b>Bool</b>

Está función pública devolvía las 4 alertas de tags digitales mas recientes pertenecientes a un grupo de estaciones dados sus id.
Su utilidad era tan escasa que se eliminó el feed digital de la sección de inicio dejando en desuso esta función.

```php
public function feedPrincipalDigital($estaciones)
    {
        if ($_SESSION['feedPrincipalDigital_estaciones'] == $estaciones) {
            return $feed = $_SESSION['feedPrincipalDigital_result'];
        }
        if ($this->conectar()) {
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

### FUNCIÓN **confirmarWidget()**

> <span style='color:green'>@params</span> <b>String</b> wid, <b>INT</b> tag, <b>INT</b> id_usuario

> <span style='color:green'>@returns</span> <b>Bool</b>

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

### FUNCIÓN **feedPrincipalCustom()**

> <span style='color:green'>@params</span> <b>INT</b> id_usuario

> <span style='color:green'>@returns</span> <b>Array</b>

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
                $conTrendDia = "";
                $n_tag = $this->obtenerNombreTag($tag);
                if (strpos($n_tag, 'Acumulado') !== false) {
                    $conTrendDia = "SELECT datos_historicos.fecha, datos_historicos.valor_acu, datos_historicos.valor_float, valor_int FROM datos_historicos WHERE id_tag=" . $tag . " AND datos_historicos.fecha::date >= current_date::date - interval '7 days' AND datos_historicos.fecha::date <= current_date::date ORDER BY fecha desc";
                } else {
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

### FUNCIÓN **borrarPreset()**

> <span style='color:green'>@params</span> <b>String</b> n_preset, <b>INT</b> id_usuario

> <span style='color:green'>@returns</span> <b>Bool</b>

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

### FUNCIÓN **leerPresets()**

> <span style='color:green'>@params</span> <b>INT</b> id_usuario

> <span style='color:green'>@returns</span> Mixed <b>Array</b> | <b>Bool</b>

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

### FUNCIÓN **guardarPreset()**

> <span style='color:green'>@params</span> <b>String</b> usuario, <b>String</b> nombre, <b>String</b> estacion, <b>Array</b> tags_colores

> <span style='color:green'>@returns</span> <b>Bool</b>

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
            $secu = "INSERT <b>INT</b>O graficas(id_usuario, configuracion)
            VALUES (" . $id_usuario[0]['id_usuario'] . ", '" . $codigo . "')";
            pg_query($this->conexion, $secu);
            return true;
        }
        return false;
    }
```

---

## **2.2 CLASE DataWit.php**

> importante: esta clase se apoya en los drivers de SQLServer para <span style='color:purple'><b>PHP</b></span> (sqlsrv) no incluidos en apache.

> Los contenidos de esta clase son provisionales y están sujetos a posibles cambios.

Esta clase contiene las funciones para acceder a las tablas en bases de datos de SQL Server, dónde se encuentran las configuraciones, datos y funciones de las consignas y los plannings de las señales provenientes de WIT. Al no compartir la arquitectura con <span style='color:darkblue;'><b>Aquando</b></span>\_ddbb, también tiene una conexión auxiliar a otra base de datos con la información necesaria para relacionar la información de <span style='color:darkblue;'><b>Aquando</b></span> con la del entorno WIT.

La clase instancia al principio las variables y constantes necesarias para establecer conexiones al servidor de SQLServer. La función de constructor se encuentra vacía y la clase no requiere de ningún parámetro.

### FUNCIÓN **conectar()**

> <span style='color:green'>@params</span> Void

> <span style='color:green'>@returns</span> Mixed Sqlstmt | <b>Bool</b>

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

### FUNCIÓN **conectarAux()**

> <span style='color:green'>@params</span> Void

> <span style='color:green'>@returns</span> Mixed Sqlstmt | <b>Bool</b>

Función privada que establece una conexión auxiliar con _Conversion_<span style='color:darkblue;'><b>Aquando</b></span>\_. Está base de datos tiene las relaciones entre el entorno de <span style='color:darkblue;'><b>Aquando</b></span> y el de WIT

```php
private function conectarAux()
    {
        if (!$this->conexionAux) {
            $this->info_server = array("Database" => "Conversion_<span style='color:darkblue;'><b>Aquando</b></span>", "Uid" => "sa", "PWD" => "dateando", "Encrypt" => false);
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

### FUNCIÓN **consultaExitosa()**

> <span style='color:green'>@params</span> Sqlstmt

> <span style='color:green'>@returns</span> <b>Bool</b>

Función privada que comprueba los resultados de una consulta.

```php
private function consultaExitosa($stmt)
    {
        if ($stmt) {
            return true;
        } else {
            print_r(sqlsrv_errors());
            return false;
        }
    }
```

### FUNCIÓN **consignasEstacion()**

> <span style='color:green'>@params</span> <b>String</b> estacion

> <span style='color:green'>@returns</span> Mixed <b>Array</b> | <b>Bool</b>

Función pública que obtinene las consignas que existen en una estación dada su nombre.
Se usa en las secciones de estacion > ajustes

```php
    public function consignasEstacion($estacion)
    {
        if ($this->conectarAux() && $estacion != "Deposito Berroa") {
            $consulta = "SELECT * FROM Info_lkv where estacion like('%" . $estacion . "%') AND nombre_tag like ('%Consigna%') AND Nombre_variable_wit LIKE('%Import%')";
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

### FUNCIÓN **leerConsignaWit()**

> <span style='color:green'>@params</span> <b>String</b> recurso

> <span style='color:green'>@returns</span> Mixed <b>Array</b> | <b>Bool</b>

Función pública que obtiene los valores de una consigna determinada dada su identificación dentro del entorno WIT.
Se usa en las secciones de estacion > ajustes

```php
    public function leerConsignaWIT($recurso)
    {
        if ($this->conectar()) {
            $consulta = "SELECT * FROM [DBEASY452].[dbo].[WValue] WHERE ValueWOSAdd LIKE('%" . $recurso . "%') AND ValueWOSAdd LIKE('%InLink%')";
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

### FUNCIÓN **modificarConsignaWit()**

> <span style='color:green'>@params</span> <b>String</b> ref, Float valor

> <span style='color:green'>@returns</span> <b>String</b>

Función pública que modifica el valor de una consigna WIT dada su referencia dentro del entorno WIT por un valor dado en los parámetros.

```php
    public function modificarConsignaWit($ref, $valor)
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

### FUNCIÓN **leerPlanningsEstacion()** --> WIP

> <span style='color:green'>@params</span> ?

> <span style='color:green'>@returns</span> ?

_Está función aun no está implementada y su código no es definitivo._
Esta función pública se encarga de listar todos los plannnings existentes de una estación determinada.

```php
//Código no disponible
```

### FUNCIÓN **leerValorPlanning()** --> WIP

> <span style='color:green'>@params</span> ?

> <span style='color:green'>@returns</span> ?

_Está función aun no está implementada y su código no es definitivo._
Esta función pública se encarga de leer el valor de un planning dada su referencia.

```php
//Código no disponible
```

### FUNCIÓN **modificarPlanning()** --> WIP

> <span style='color:green'>@params</span> ?

> <span style='color:green'>@returns</span> ?

_Está función aun no está implementada y su código no es definitivo._
Esta función pública se encarga de modificar el valor de un planning determinado por una configuración nueva determinada.

```php
//Código no disponible
```

---

# **3. MODELOS**

Los modelos son clases de <span style='color:purple'><b>PHP</b></span> situadas bajo el directorio _App/Models_ sobre las cuales se apoyan tanto el controlador, como los archivos en Ajax para funcionar. Estas incluyen funciones especiales y conforman los objetos con los que se trabaja en <span style='color:darkblue;'><b>Aquando</b></span>.
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

### FUNCIÓN **save()**

> <span style='color:green'>@params</span> Void

> <span style='color:green'>@returns</span> Void

Fucnion pública que se encarga de guardar datos de usuario apoyandose en otras funciones de la clase.

```php
public function save()
    {
        $this->db->updateUserData($this->uData[0]['id_usuario'], $this->uData[0]['id_usuario']['password']);
    }
```

### FUNCIÓN **loginUsuario()**

> <span style='color:green'>@params</span> <b>String</b> password

> <span style='color:green'>@returns</span> <b>Bool</b>

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
            return true;
        }
        return false;
    }
```

### FUNCIÓN **setPassword()**

> <span style='color:green'>@params</span> <b>String</b> password

> <span style='color:green'>@returns</span> Void

Función pública que cifra la contraseña en uData según los parámetros preestablecidos.

```php
public function setPassword($password)
    {
        $this->uData[0]['hash'] = password_hash($password, self::HASH, ['cost' => self::COST]);
    }
```

### FUNCIÓN **hashear()**

> <span style='color:green'>@params</span> <b>String</b> pwd

> <span style='color:green'>@returns</span> <b>String</b>

Función pública que se usa como debug. Devuelve el hash de cualquier texto.

```php
public function hashear($pwd)
    {
        return password_hash($pwd, self::HASH, ['cost' => self::COST]);
    }
```

### FUNCIÓN **getHash()**

> <span style='color:green'>@params</span> Void

> <span style='color:green'>@returns</span> <b>String</b>

Función getter estándar de la propiedad _hash_ de la clase.

## **3.2 CLASE Usuario.php**

Esta es la clase que define el objeto usuario usado en el controlador y en los archivos Ajax.
Contiene las distintas acciones que puede hacer un usuario en <span style='color:darkblue;'><b>Aquando</b></span>, aunque no todas.

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

Esta clase se usa cuando hay imputs que tenga que introducir el usuario (generalmente los de texto) y se encarga de que no contengan contenidos perjudiciales para el funcionamiento de la aplicación.

> De momento funciona solamente si la llama el controlador pero el objetivo es que funciones también con las de <span style='color:red'><b>AJAX</b></span>.

> El código de esta clase no es definitivo y está sujeto a cambios.

> Todas las funciones son públicas

Esta clase instancia varios diccionarios con elementos que no pueden introducirse en los imputs.
Está el diccionario de texto, el diccionario de números, el de carácteres especiales y otro de carácteres especiales menos estricto.

> En el constructor se incluye la función **str_tiene()** (viene a ser un **str_contains()** pero que como algunas clases usan esta función generaba problemas y tuve que cambiarle el nombre). No requiere de parámetros para ser instanciada.

### FUNCIÓN **limpiar()**

> <span style='color:green'>@params</span> <b>String</b> elem

> <span style='color:green'>@returns</span> <b>String</b>

Esta función elimina los carácteres las habituales.

```php
public function limpiar($elem)
    {
        $elem =  str_replace(' ', '-', $elem);
        return preg_replace('/[^A-Za-z0-9\-]/', '', $elem);
    }
```

### FUNCIÓN **valTextoGen()**

> <span style='color:green'>@params</span> <b>String</b> elem

> <span style='color:green'>@returns</span> <b>Bool</b>

Comprueba que el input es un texto genérico válido (inluyendo números).

```php
    public function valTextoGen($texto)
    {
        $texto = "" . $texto . "";
        $dic = $this->diccionarioEspecial;
        for ($i = 0; $i < sizeof($dic); $i++) {
            if (str_tiene($texto, $dic[$i])) {
                return false;
            }
        }
        return true;
    }
```

### FUNCIÓN **valTextoLimpio()**

> <span style='color:green'>@params</span> <b>String</b> texto

> <span style='color:green'>@returns</span> <b>Bool</b>

Valida que el input es texto estrictamente.

```php
    public function valTextoLimpio($texto)
    {
        $texto = "" . $texto . "";
        $dic = array_merge($this->diccionarioEspecial, $this->diccionarioNum);
        for ($i = 0; $i < sizeof($dic); $i++) {
            if (str_tiene($texto, $dic[$i])) {
                return false;
            }
        }
        return true;
    }
```

### FUNCIÓN **valNum()**

> <span style='color:green'>@params</span> <b>String</b> num

> <span style='color:green'>@returns</span> <b>Bool</b>

Comprueba que el input se trata de un número válido

```php
public function valconfig($config)
    {
        $config = "" . $config . "";
        $dicExcep = array("5", "6", "7", "8", "9");
        $dic = array_merge($dicExcep, $this->diccionarioTexto, $this->diccionarioEspecial);
        for ($i = 0; $i < sizeof($dic); $i++) {
            if (str_tiene($config, $dic[$i])) {
                return false;
            }
        }
        return true;
    }
```

### FUNCIÓN **valFecha()**

> <span style='color:green'>@params</span> <b>String</b> fecha

> <span style='color:green'>@returns</span> <b>Bool</b>

Comprueba que el input es una fecha válida.
No se fija en el formato.

```php
public function valFecha($fecha)
    {
        $fecha = "" . $fecha . " ";
        $dic = array_merge($this->diccionarioTexto, $this->diccionarioEspecialMin);
        for ($i = 0; $i < sizeof($dic); $i++) {
            if (str_tiene($fecha, $dic[$i])) {
                return false;
            }
        }
        return true;
    }
```

---

# **4. Vistas / Secciones**

Las vistas son archivos .php que contienen el código html de las secciones. Responden a la información de contexto enviada del controlador. También suelen incorporar una paqueña porción del <span style='color:gold'><b>JS</b></span> que las acompaña.

### 4.1 VISTA inicio.php

Esta sección es la principal de la aplicación. Inluye todas las dependencias de código comunes del resto de secciones.

Contiene el display principal incluyendo los menús de navegación, tips de alarma y banners. Es la que se muestra al iniciar la aplicación y también es la sección que se muestra cuando un usuario inicia sesión lo que significa qie inicio.php es el **display y sección desconectado.**

> En ella aparecen el resto de secciones y la sección de alarmas sur.

> Tiene varios archivos <span style='color:gold'><b>JS</b></span> dedicados: ayuda.js, desconectado.js, mlat.js, reloj.js y una vez iniciada sesión, sur.js

> Tiene algunos archivos <span style='color:red'><b>AJAX</b></span> dedicados: A_reloj.php, A_Sur.php

### 4.2 VISTA principal.php

Esta vista nace de inicio.php y crea la sección principal una vez se inicia sesión. Tiene dependencias propias únicas y convierte una gran cantidad de información del controlador a <span style='color:gold'><b>JS</b></span>.

### 4.3 VISTA inicioSesion.php

Esta sección nace de inicio.php es la que tiene el formulario de ingreso de sesión.

### 4.4 VISTA estacion.php

Esta sección nace de inicio.php y recibe información de contexto del controlador para mostrar la vista de una estación concreta.
Esta vista tiene dependencias propias.
También declara parte de la información del controlador en Js para poder trabajar con ella.

> Tiene un archivo js dedicado: estaciones.js

> Tiene varios archivos <span style='color:red'><b>AJAX</b></span> dedicados: A_Estacion.php, A_Ajustes.php

### 4.5 VISTA graficas.php

Esta vista nace de inicio.php y compone la sección de vista rápida de las gráficas.
Esta vista tiene dependencias propias.
Esta vista también convierte información del controlador a <span style='color:gold'><b>JS</b></span> en el codigo que incluye.

> Tiene un archivo js dedicado: graficas.js

> Tiene un archivo <span style='color:red'><b>AJAX</b></span> dedicado: A_Graficas.php

### 4.6 VISTA graficasCustom.php

Esta vista nace de incio.php y compone la sección de vista personalizada dentro de las gráficas.
Esta vista tiene dependencias propias.
Esta vista también convierte información del controlador a <span style='color:gold'><b>JS</b></span> en el codigo que incluye.

> Tiene un archivo js dedicado: graficasCustom.js

> Tiene un archivo <span style='color:red'><b>AJAX</b></span> dedicado: A_GraficasCustom.php

### 4.7 VISTA alarmas.php

Esta vista nace de inicio.php y crea la sección del explorador de alarmas.
No tiene dependencias especiales.
Requiere de convertir información del controlador a <span style='color:gold'><b>JS</b></span> y tiene su archivo dedicado.

> Tiene un archivo js dedicado: alarmas.js

> Tiene un archivo <span style='color:red'><b>AJAX</b></span> dedicado: A_Alarmas.php

### 4.8 VISTA informes.php

Esta vista nace de inicio.php y crea la sección de generación de informes.
No tiene dependencias especiales.
Convierte una escasa cantidad de información del controlador a <span style='color:gold'><b>JS</b></span> pero si que tiene archivos dedicados.

> Tiene un archivo js dedicado: informes.js

> Tiene un archivo <span style='color:red'><b>AJAX</b></span> dedicado: A_Informes.php

### 4.8 VISTA comunicaciones.php

Esta vista nace de inicio.php y crea la sección de comunicaciones.
No tiene apenas dependencias pero si que convierte información del controlador a <span style='color:gold'><b>JS</b></span>.

> Tiene un archivo js dedicado: comunicaciones.js

> Tiene un archivo <span style='color:red'><b>AJAX</b></span> dedicado: A_Conexiones.php

---

# **5. LADO CLIENTE**

Con lado cliente incluyo todos los archivos en JavaScript y <span style='color:red'><b>AJAX</b></span>. Básicamente todo lo que se ejecuta desde fuera del servidor o dependiendo de las acciones del cliente.

## 5.1 JavaScript

Existen multitud de archivos <span style='color:gold'><b>JS</b></span> que se encargan de controlar todos los eventos nacidos de la interfaz y la interacción del cliente pero también tienen funciones que comunican con <span style='color:red'><b>AJAX</b></span> para llevar a cabo operaciones con el servidor en tiempo real.

> En este documento solo se resaltan estas funciones de peticiones por <span style='color:red'><b>AJAX</b></span> al ser las verdaderamente importantes.

### 5.1.1 **alarmas.js**

#### 5.1.1.1 FUNCIÓN **actualizar()**

> <span style='color:green'>@params</span> <b>String</b> reorden

> <span style='color:green'>@returns</span> <b>HTML</b>

Esta función hace una llamada a Servidor (A_Alarmas.php) a través de Ajax para actualizar la información en pantalla con la misma configuración que estuviese aplicada por el usuario en el momento de que se ejecute.

Petición:

```js
    $(document).ready(function () {
      $.ajax({
        type: "POST",
        url: "/<span style='color:darkblue;'><b>Aquando</b></span>.com/A_Alarmas.php",
        data: {
          funcion: "actualizar",
          nombre: nombre,
          emp: emp,
          sentido: sentido,
          orden: orden,
          fechaInicio: fechaInicio,
          fechaFin: fechaFin,
        },
        success: function (alarmas) {
          document.getElementById("tablaAlarmas").inner<b>HTML</b> = alarmas;
        },
        error: function (e) {
          console.log("error");

        },
      });
    });
```

#### 5.1.1.2 FUNCIÓN **filtrarPorEstacion()**

> <span style='color:green'>@params</span> Void

> <span style='color:green'>@returns</span> <b>HTML</b>

Esta función hace una llamada a Servidor (A_Alarmas.php) a través de Ajax para extraer sólo las alarmas de una estación en particular.

Petición:

```js
    $(document).ready(function () {
      $.ajax({
        type: "POST",
        url: "/<span style='color:darkblue;'><b>Aquando</b></span>.com/A_Alarmas.php",
        data: {
          funcion: "estacion",
          sentido: sentido,
          orden: orden,
          estacion: id_estacion,
          fechaInicio: fechaInicio,
          fechaFin: fechaFin,
        },
        success: function (alarmas) {
          document.getElementById("tablaAlarmas").inner<b>HTML</b> = alarmas;
        },
        error: function (e) {
          console.log("error");
        },
      });
    });
```

#### 5.1.1.3 FUNCIÓN **reconocer()**

> <span style='color:green'>@params</span> <b>INT</b> id_alarma

> <span style='color:green'>@returns</span> Void

Esta función hace una llamada a Servidor (A_Alarmas.php) a través de Ajax para reconocerla a la fecha actual con el usuario que esté logueado en ese momento.

Petición:

```js
$(document).ready(function () {
  $.ajax({
    type: "POST",
    url: "/<span style='color:darkblue;'><b>Aquando</b></span>.com/A_Alarmas.php",
    data: {
      funcion: "reconocer",
      alarma: id_alarma,
      nombre: sessionStorage.getItem("nousu"),
    },
    success: function () {
      actualizar(null);
    },
    error: function () {
      console.log("error en la update");
    },
  });
});
```

#### 5.1.1.4 FUNCIÓN **detallesAlarma()**

> <span style='color:green'>@params</span> <b>INT</b> id

> <span style='color:green'>@returns</span> <span style='color:gold'><b>JS</b></span>ON

Esta función hace una llamada a Servidor (A_Alarmas.php) a través de Ajax para extraer los datos historicos de 12h en adelante y atrás de una señal perteneciente a una alarma.

Petición:

```js
$(document).ready(function () {
  $.ajax({
    type: "POST",
    url: "/<span style='color:darkblue;'><b>Aquando</b></span>.com/A_Alarmas.php",
    data: { funcion: "detalles", id: id },
    success: function (det) {
      var nombre_estacion = det[0]["nombre_estacion"];
      var nombre_tag = det[0]["nombre_tag"];
      var fechas = [];
      var vals = [];
      for (var i = 0; i < det.length; i++) {
        for (var cosa in det[i]) {
          if (cosa == "fecha") {
            fechas.push(det[i][cosa]);
          } else {
            if (
              det[i][cosa] != null &&
              cosa != "nombre_estacion" &&
              cosa != "nombre_tag"
            ) {
              vals.push(det[i][cosa]);
            }
          }
        }
      }
      var det_p = [fechas, vals, nombre_estacion, nombre_tag];
      popDetalles(det_p);
    },
    error: function (e) {
      console.log("error en los detalles");
    },
    dataType: "json",
  });
});
```

## 5.1.2 **comunicaciones.js**

#### 5.1.2.1 FUNCIÓN **actualizarConexiones()**

> <span style='color:green'>@params</span> Void

> <span style='color:green'>@returns</span> <b>HTML</b>

Esta función hace una petición a servidor por Ajax a través de A_Conexiones.php para actualizar la información en pantalla de la sección Comunicaciones

```js
  $(document).ready(function () {
    $.ajax({
      type: "POST",
      url: "/<span style='color:darkblue;'><b>Aquando</b></span>.com/A_Conexiones.php",
      data: {
        nombre: nombre,
        pwd: pwd,
        opcion: "conex",
      },
      success: function (conex) {
        document.getElementById("tablaConex").inner<b>HTML</b> = conex;
      },
      error: function () {
        console.log("error");
      },
    });
```

#### 5.1.2.2 FUNCIÓN **nombrarEstacion()**

> <span style='color:green'>@params</span> <b>INT</b> estacion

> <span style='color:green'>@returns</span> <b>HTML</b>

Esta función hace una petición a servidor por Ajax a través de A_Conexiones.php para extraer el nombre de una estación dado solo su id.

```js
  $(document).ready(function () {
    $.ajax({
      type: "POST",
      url: "/<span style='color:darkblue;'><b>Aquando</b></span>.com/A_Conexiones.php",
      data: {
        estacion: estacion,
        opcion: "nom",
      },
      success: function (est) {
        document.getElementById("calidadSenales").inner<b>HTML</b> =
          '<h4 id="calidadSenales"> Calidad de señal: ' + est + "</h4>";
      },
      error: function () {
        console.log("error");
      },
    });
  });
```

## 5.1.3 **estaciones.js**

#### 5.1.3.1 FUNCIÓN **actualizar()**

> <span style='color:green'>@params</span> <b>INT</b> id_estacion

> <span style='color:green'>@returns</span> <span style='color:gold'><b>JS</b></span>ON

Esta función hace una petición a servidor por Ajax a través de A_Estacion.php y extrae toda la información necesaria para actualizar los widgets de la sección.

```js
    $(document).ready(function () {
      $.ajax({
        type: "POST",
        url: "/<span style='color:darkblue;'><b>Aquando</b></span>.com/A_Estacion.php",
        data: {
          opcion: "actualizar",
          estacion: id_estacion,
          tipo: "todos",
        },
        success: function (datos) {
          filtrarDatos(datos);
          sessionStorage.setItem("param_id", id_estacion);
          sessionStorage.setItem("data", <span style='color:gold'><b>JS</b></span>ON.stringify(datos));
        },
        error: function () {
          console.log("error");
        },
        dataType: "json",
      });
    });
```

#### 5.1.3.2 FUNCIÓN **trendTagsV2()**

> <span style='color:green'>@params</span> Void

> <span style='color:green'>@returns</span> <span style='color:gold'><b>JS</b></span>ON

Esta función hace una petición a servidor por Ajax a través de A_Estacion.php y extrae los trends de las señales de la estación.

```js
    $(document).ready(function () {
      $.ajax({
        type: "POST",
        data: {
          opcion: "t_trend",
          arrTags: arrTags,
          tipo: "todos",
        },
        url: "/<span style='color:darkblue;'><b>Aquando</b></span>.com/A_Estacion.php",
        success: function (trends) {
          var arrTrends = [];
          for (var a in trends) {
            arrTrends[a] = { fecha: [], max: [] };
            for (var b in trends[a]) {
              arrTrends[a]["fecha"].push(trends[a][b]["fecha"]);
              if (trends[a][b]["acu"] != null) {
                arrTrends[a]["max"].push(trends[a][b]["acu"]);
              }
              if (trends[a][b]["float"] != null) {
                arrTrends[a]["max"].push(trends[a][b]["float"]);
              }
              if (trends[a][b]["int"] != null) {
                arrTrends[a]["max"].push(trends[a][b]["int"]);
              }
            }
          }
          montarWidgetsAnalogicos();
          todoTrends = arrTrends;
          montarWidgetsDigi();
          sessionStorage.setItem("trend_arrTags", <span style='color:gold'><b>JS</b></span>ON.stringify(arrTags));
          sessionStorage.setItem("trend_todoTrends", <span style='color:gold'><b>JS</b></span>ON.stringify(arrTrends));

        },
        error: function () {
          console.log("error");
        },
        dataType: "json",
      });
```

#### 5.1.3.3 FUNCIÓN **fotoEstacion()**

> <span style='color:green'>@params</span> <b>INT</b> id_estacion

> <span style='color:green'>@returns</span> <b>HTML</b>

Esta función hace una petición a servidor por Ajax a través de A_Estacion.php y extrae la foto en Base64 perteneciente a una estación

```js
$(document).ready(function () {
  $.ajax({
    type: "POST",
    data: {
      opcion: "foto",
      estacion: id_estacion,
    },
    url: "/<span style='color:darkblue;'><b>Aquando</b></span>.com/A_Estacion.php?",
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
```

> aun faltaría añadir las peticiones a A_Ajustes.php pero el código es provisional y está sujeto a cambios.

## 5.1.4 **graficas.js**

#### 5.1.4.1 FUNCIÓN **metaDatostag()**

> <span style='color:green'>@params</span> <b>INT</b> id_tag, <b>INT</b> id_estacion

> <span style='color:green'>@returns</span> <span style='color:gold'><b>JS</b></span>ON

Esta función hace una petición a servidor por Ajax a través de A_Graficas.php y obtiene los calculados de una señal en función de una serie de parámetros y dados el id de la señal y la estación.

```js
$.ajax({
  type: "POST",
  url: "/<span style='color:darkblue;'><b>Aquando</b></span>.com/A_Graficas.php",
  data: {
    opcion: "meta",
    tag: id_tag,
    estacion: id_estacion,
  },
  success: function (meta) {
    datosM["max"] = meta["max"];
    datosM["min"] = meta["min"];
    datosM["avg"] = meta["avg"];
    $.ajax({
      type: "POST",
      url: "/<span style='color:darkblue;'><b>Aquando</b></span>.com/A_Graficas.php",
      data: {
        estacion: id_estacion,
        tag: id_tag,
        opcion: "render",
      },
      success: function (histo) {
        datosR = histo;
        renderGrafico(datosR);
      },
      error: function (e) {
        console.log(e);
      },
      dataType: "json",
    });
  },
  error: function (e) {
    console.log(e);
  },
  dataType: "json",
});
```

#### 5.1.4.2 FUNCIÓN **tagsEstacion()**

> <span style='color:green'>@params</span> <b>INT</b> id_estacion

> <span style='color:green'>@returns</span> <b>HTML</b>

Esta función hace una petición a servidor por Ajax a través de A_Graficas.php para extraer una lista de los tags disponibles según una estación dado su id.

```js
$(document).ready(function () {
    $.ajax({
      type: "POST",
      url: "/<span style='color:darkblue;'><b>Aquando</b></span>.com/A_Graficas.php",
      data: { estacion: id_estacion, opcion: "tags" },
      success: function (tags) {
        document.getElementById("opcionesTag").inner<b>HTML</b> = "";
        document.getElementById("compararSel").inner<b>HTML</b> =
          "<option value='nada' selected>Nada</option>";
        var e = 0;
        sessionStorage.setItem("tagsAct", <span style='color:gold'><b>JS</b></span>ON.stringify(tags));
        for (var tag in tags) {
          if (e == 0) {
            document.getElementById("opcionesTag").inner<b>HTML</b> +=
              "<option value=" +
              tags[tag]["id_tag"] +
              " selected>" +
              tags[tag]["nombre_tag"] +
              "</option>";
          } else {
            document.getElementById("opcionesTag").inner<b>HTML</b> +=
              "<option value=" +
              tags[tag]["id_tag"] +
              ">" +
              tags[tag]["nombre_tag"] +
              "</option>";
          }
          document.getElementById("compararSel").inner<b>HTML</b> +=
            "<option value=" +
            tags[tag]["id_tag"] +
            ">" +
            tags[tag]["nombre_tag"] +
            "</option>";
          e++;
        }
        aplicarOpciones();
      },
      error: function () {
        console.log("error");
      },
      dataType: "json",
    });
  });
```

## 5.1.5 **graficasCustom.js**

#### 5.1.5.1 FUNCIÓN **tagsEstacionCustom()**

> <span style='color:green'>@params</span> <b>INT</b> id_estacion

> <span style='color:green'>@returns</span> <b>HTML</b>

Esta función hace una petición a servidor por Ajax a través de A_GraficasCustom.php para extraer una lista de los tags disponibles según una estación dado su id.

```js
$.ajax({
      type: "POST",
      url: "/<span style='color:darkblue;'><b>Aquando</b></span>.com/A_Graficas.php",
      data: { estacion: id_estacion, opcion: "tags" },
      success: function (tags) {
        document.getElementById("opcionesTag").inner<b>HTML</b> = "";
        var e = 0;
        sessionStorage.setItem("tagsAct", <span style='color:gold'><b>JS</b></span>ON.stringify(tags));
        for (var tag in tags) {
          if (e == 0) {

            document.getElementById("opcionesTag").inner<b>HTML</b> +=
              '<li><input type="checkbox" name="checkTag" style="visibility: hidden;" value="' +
              tags[tag]["id_tag"] +
              '" id = ' +
              tags[tag]["id_tag"] +
              '><label for = "' +
              tags[tag]["id_tag"] +
              '" style="box-sizing: none"> ' +
              tags[tag]["nombre_tag"] +
              ' </label> <label> <i class= "fas fa-palette"> </i><input type="color" class="form-control-color" id="color' +
              tags[tag]["id_tag"] +
              '" style="visibility:hidden" title="color" name="colorDato" list="coloresTagGraf"></label></li>';
          } else {
            document.getElementById("opcionesTag").inner<b>HTML</b> +=
              '<li> <input type = "checkbox" name="checkTag" style = "visibility: hidden;" value="' +
              tags[tag]["id_tag"] +
              '" id = ' +
              tags[tag]["id_tag"] +
              ' ><label for = "' +
              tags[tag]["id_tag"] +
              '" style="box-sizing: none"> ' +
              tags[tag]["nombre_tag"] +
              ' </label> <label> <i class= "fas fa-palette"> </i><input type="color" class="form-control-color" id="color' +
              tags[tag]["id_tag"] +
              '" style="visibility:hidden" title="color" name="colorDato" list="coloresTagGraf"></label ></li>';
          }
          e++;
        }
      },
      error: function () {
        console.log("error");
      },
      dataType: "json",
    });
  });
```

#### 5.1.5.2 FUNCIÓN **infoTags()**

> <span style='color:green'>@params</span> <b>INT</b> estacion, <b>Array</b> ajustesTag, <b>INT</b> tag, <b>Array</b> metas, <b>TS</b> fechaIni, <b>TS</b> fechaFin

> <span style='color:green'>@returns</span> <span style='color:gold'><b>JS</b></span>ON

Esta función hace una petición a servidor por Ajax a través de A_GraficasCustom.php para conseguir los calculados y metadados de un tag.

```js
$.ajax({
  type: "POST",
  url: "/<span style='color:darkblue;'><b>Aquando</b></span>.com/A_GraficasCustom.php",
  data: {
    estacion: estacion,
    id_tag: tag,
    fechaIni: fechaIni,
    fechaFin: fechaFin,
    meta: metas,
    opcion: "tag",
  },
  success: function (datosTag) {
    prepararTag(datosTag, tag);
    if (ajustesTag.at(-1) == tag) {
      setTimeout(renderGrafico, nTags * 300);
    }
  },
  error: function (e) {
    console.log("error");
  },
  dataType: "json",
});
```

#### 5.1.5.3 FUNCIÓN **leerPresets()**

> <span style='color:green'>@params</span> <b>String</b> para

> <span style='color:green'>@returns</span> Mixed <span style='color:gold'><b>JS</b></span>ON | <b>HTML</b>

Esta función hace una petición a servidor por Ajax a través de A_GraficasCustom.php para leer los presets guardados por un usuario.

leer presets:

```js
    $(document).ready(function () {
      $.ajax({
        type: "POST",
        url: "/<span style='color:darkblue;'><b>Aquando</b></span>.com/A_GraficasCustom.php",
        data: {
          opcion: "leerPresets",
          para: para,
          arrdatos: arrdatos,
        },
        success: function (presets) {
          document.getElementById("selPresets").inner<b>HTML</b> = presets;
        },
        error: function (e) {
          console.log("error");
        },
      });
    });
```

cargar presets:

```js
$(document).ready(function () {
  $.ajax({
    type: "POST",
    url: "/<span style='color:darkblue;'><b>Aquando</b></span>.com/A_GraficasCustom.php",
    data: {
      opcion: "leerPresets",
      para: para,
      arrdatos: arrdatos,
    },
    success: function (presets) {
      presets_config = presets;
      for (var index in presets_config) {
        if (presets_config[index]["configuracion"].includes(n_preset)) {
          var config = presets_config[index]["configuracion"];
          config = config.substring(config.indexOf("@") + 1);
          var id_est = config.substring(0, config.indexOf("?"));
          var config_tags = config.substring(config.indexOf("/") + 1);
          var tagsycolores = config_tags.split("/");
          var config_tags_colores = new (<b>Array</b>)();
          for (var index in tagsycolores) {
            var info = tagsycolores[index].split(":");
            config_tags_colores[info[0]] = info[1];
            document.getElementById(info[0]).checked = "true";
            document.getElementById("color" + info[0]).value = info[1];
            if (
              document.getElementById(info[0]).parentNode.style
                .backgroundColor == "darkgray"
            ) {
              document.getElementById(
                info[0]
              ).parentNode.style.backgroundColor = "lightgray";
            } else {
              document.getElementById(
                info[0]
              ).parentNode.style.backgroundColor = "darkgray";
            }
            document.getElementById("color" + info[0]).parentNode.style.color =
              info[1];
          }
          aplicarCustom();
          ajustesPresets(null);
        }
      }
    },
    error: function (e) {
      console.log("error");
    },
    dataType: "json",
  });
});
```

#### 5.1.5.4 FUNCIÓN **borrarPreset()**

> <span style='color:green'>@params</span> Void

> returns <b>Bool</b>

Esta función hace una petición a servidor por Ajax a través de A_GraficasCustom.php para borrar el preset seleccionado en ese momento.

```js
$(document).ready(function () {
  $.ajax({
    type: "POST",
    url: "/<span style='color:darkblue;'><b>Aquando</b></span>.com/A_GraficasCustom.php",
    data: {
      opcion: "borrar",
      preset: n_preset,
      arrdatos: arrdatos,
    },
    success: function () {
      leerPresets("mostrar");
      setTimeout(ajustesPresets(null), 1000);
    },
    error: function (e) {
      console.log("error");
    },
    dataType: "json",
  });
});
```

#### 5.1.5.5 FUNCIÓN **guardarPreset()**

> <span style='color:green'>@params</span> Void

> @return Void

Esta función hace una petición a servidor por Ajax a través de A_GraficasCustom.php para guardar un preset según los ajustes seleccionados en ese momento para el usuario que esté logueado en ese momento.

```js
$(document).ready(function () {
      $.ajax({
        type: "POST",
        url: "/<span style='color:darkblue;'><b>Aquando</b></span>.com/A_GraficasCustom.php",
        data: {
          opcion: "guardar",
          arrDatosPreset: arrDatosPreset,
        },
        success: function (info) {
          document.getElementById("ajustesPresets").inner<b>HTML</b> +=
            "preset guardado";
          leerPresets("mostrar");
          setTimeout(ajustesPresets(null), 1000);
        },
        error: function () {
          console.log("error en el guardado");
        },
        dataType: "json",
      });
    });
```

## 5.1.6 **informes.js**

#### 5.1.6.1 FUNCIÓN **obtenerInforme()**

> <span style='color:green'>@params</span> Void

> <span style='color:green'>@returns</span> <b>HTML</b>

Esta función hace una peticion a servidor por Ajax a través de A_Informes.php para obtener los datos necesarios para crear un informe a traves de Koolreport según las opciones seleccionadas en ese momento por el usuario.

```js
$.ajax({
      type: "POST",
      data: {
        opcion: opcion,
        fechaIni: fInicio,
        fechaFin: fFin,
        arrEstaciones: arrEstaciones,
        arrNombres: arrNombres,
      },

      url: "/<span style='color:darkblue;'><b>Aquando</b></span>.com/A_Informes.php",
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
        document.getElementById("espacioInforme").inner<b>HTML</b> += cabecera;
        document.getElementById("espacioInforme").inner<b>HTML</b> += informe;
        document.getElementById("espacioInforme").inner<b>HTML</b> += pie;
      },
      error: function () {
        console.log("error en los informes");
      },
    });
  });
```

## 5.1.7 **principal.js**

#### 5.1.7.1 FUNCIÓN **actualizar()**

> <span style='color:green'>@params</span> Void

> <span style='color:green'>@returns</span> <span style='color:gold'><b>JS</b></span>ON

Esta función hace una petición a servidor por Ajax a través de A_Principal.php para actualizar la información de los widgets de la sección principal.

```js
$(document).ready(function () {
  $.ajax({
    type: "POST",
    url: "/<span style='color:darkblue;'><b>Aquando</b></span>.com/A_Principal.php",
    data: {
      opcion: "refresh",
      arrdatos: arrdatos,
    },
    success: function (feedDigi) {
      feedDigital = feedDigi;
      feedPrincipalCustom();
      renderFeedDigi();
    },
    error: function () {
      console.log("refresh error");
      feedPrincipalCustom();
    },
    dataType: "json",
  });
});
```

#### 5.1.7.2 FUNCIÓN **cargarAjustes()**

> <span style='color:green'>@params</span> Void

> <span style='color:green'>@returns</span> <b>HTML</b>

Esta función hace una petición a servidor por Ajax a través de A_Principal.php para obtener la lista de tags compatibles con la configuración de la sección (los widgets personalizados por el usuario) principal.

```js
$(document).ready(function () {
      $.ajax({
        type: "POST",
        url: "/<span style='color:darkblue;'><b>Aquando</b></span>.com/A_Principal.php",
        data: {
          opcion: "ajustes",
          arrEstaciones: arrEstaciones,
        },
        success: function (tagsAnalog) {
          listaTags = tagsAnalog;
          sessionStorage.setItem("listaTags", <span style='color:gold'><b>JS</b></span>ON.stringify(listaTags));
          for (var deposito in tagsAnalog) {
            sel.inner<b>HTML</b> +=
              "<optgroup label = '" +
              tagsAnalog[deposito][0]["nombre_estacion"] +
              "'>";
            for (var tag in tagsAnalog[deposito]) {
              var n_tag = tagsAnalog[deposito][tag]["nombre_tag"];
              var id_tag = tagsAnalog[deposito][tag]["id_tag"];
              sel.inner<b>HTML</b> +=
                "<option value=" + id_tag + ">" + n_tag + "</option>";
            }
            sel.inner<b>HTML</b> += "</optgroup>";
          }
          sessionStorage.setItem("mapas_arrEstaciones", arrEstaciones);
          sessionStorage.setItem("mapas_listaTags", <span style='color:gold'><b>JS</b></span>ON.stringify(listaTags));
          sessionStorage.setItem("mapas_sel", sel.inner<b>HTML</b>);
        },
        error: function () {
          console.log("error de ajustes");
        },
        dataType: "json",
      });
    });
  }
```

#### 5.1.7.3 FUNCIÓN **confirmarAjusteswidget()**

> <span style='color:green'>@params</span> <b>String</b> wid

> <span style='color:green'>@returns</span> <b>HTML</b>

Esta función hace una petición a servidor por Ajax a través de A_Principal.php para guardar la configuración de un widget en la sección principal una vez configurado por el usuario.

```js
  $(document).ready(function () {
    $.ajax({
      type: "POST",
      url: "/<span style='color:darkblue;'><b>Aquando</b></span>.com/A_Principal.php",
      data: { opcion: "confirmar", wid: widget, tag: tag, usu: usu },
      success: function () {
        document.getElementById("seccionAjustes").inner<b>HTML</b> +=
          "<br><div id='ajustesRespuesta'>widget configurado con éxito</div>";
        sessionStorage.setItem("feed_usu", null);
        sessionStorage.setItem("mapas_arrEstaciones", null);
        feedPrincipalCustom();
      },
      error: function () {
        console.log("error de confirmación");
      },

    });
  });
```

#### 5.1.7.4 FUNCIÓN **feedPrincipalCustom()**

> <span style='color:green'>@params</span> Void

> <span style='color:green'>@returns</span> <span style='color:gold'><b>JS</b></span>ON

Esta función hace una petición a servidor por Ajax a través de A_Principal.php para obtener los datos (analógicos) de los widgets personalizados del usuario.

```js
    $(document).ready(function () {
      $.ajax({
        type: "POST",
        url: "/<span style='color:darkblue;'><b>Aquando</b></span>.com/A_Principal.php",
        data: { opcion: "feed", usu: usu },
        success: function (feedAna) {
          sessionStorage.setItem("feed_usu", usu);
          sessionStorage.setItem("feed_feedAna", <span style='color:gold'><b>JS</b></span>ON.stringify(feedAna));
          renderPrincipalCustom(feedAna);
        },
        error: function (e) {
          console.log("error feed principal analog");

        },
        dataType: "json",
      });
    });
```

#### 5.1.7.5 FUNCIÓN **actualizar()** --> obsoleto

> <span style='color:green'>@params</span> Void

> <span style='color:green'>@returns</span> <span style='color:gold'><b>JS</b></span>ON

Esta función hace una petición a servidor por Ajax a través de A_Principal.php para obtener los datos de los widgets digitales del usuario. Esta llamada ya no se realiza dado que la sección de widgets digitales fué eliminada aunque no ha sido eliminada por la posibilidad de reimplementarla en un futuro.

```js
$(document).ready(function () {
  $.ajax({
    type: "POST",
    url: "/<span style='color:darkblue;'><b>Aquando</b></span>.com/A_Principal.php",
    data: {
      opcion: "refresh",
      arrdatos: arrdatos,
    },
    success: function (feedDigi) {
      feedDigital = feedDigi;
      feedPrincipalCustom();
      renderFeedDigi();
    },
    error: function () {
      console.log("refresh error");
      feedPrincipalCustom();
    },
    dataType: "json",
  });
});
```

## 5.1.8 **sur.js**

#### 5.1.8.1 FUNCIÓN **actualizarSur()**

> <span style='color:green'>@params</span> <b>String</b> entorno, <b>String</b> nombre, <b>INT</b> estacion

> @return <b>HTML</b>

Esta función hace una petición a servidor por Ajax a través de A_Sur.php para obtener las alarmas del menú sur en algunas secciones. Dependiendo de la sección hace una petición para alarmas generales y en la sección de estación hace una petición distinta para obtener sólo las de la estación seleccionada.

General:

```js
      $(document).ready(function () {
        $.ajax({
          type: "POST",
          url: "/<span style='color:darkblue;'><b>Aquando</b></span>.com/A_Sur.php",
          data: { caso: "general", nombre: nombre },
          success: function (alarmas) {
            document.getElementById("alarmasSur").inner<b>HTML</b> = alarmas;
          },
          error: function () {
            console.log("error");
          },
        });
      });
```

Particular:

```js
      $(document).ready(function () {
        $.ajax({
          type: "POST",
          url: "/<span style='color:darkblue;'><b>Aquando</b></span>.com/A_Sur.php",
          data: { caso: "estacion", estacion: estacion },
          success: function (alarmas) {
            document.getElementById("alarmasSur").inner<b>HTML</b> = alarmas;
          },
          error: function () {
            console.log("error");
          },
        });
      });
```

---

## 5.2 <span style='color:red'><b>AJAX</b></span> (<span style='color:purple'><b>PHP</b></span>)

Los archivos Ajax son mini-controladores dedicados en las secciones, que se encargan de recibir peticiones desde el lado cliente para hacer los procesos que requieran conexiones a bases de datos o tratar con información sensible.
De esta forma se agrega una capa de seguridad y conseguimos hacer procesos en tiempo real que requieran del servidor sin tener que recargar la página.

Estos archivos Ajax están presentes en casi todas las secciones, como en la sección principal, las vistas de estación, las secciones de gráficas, secciones de alarmas, sección de informes y en menor medida la de comunicaciones.

> Se comunican con el lado cliente a través de peticiones de mótodo POST.

(En órden alfabético)

### 5.2.1 **A_Ajustes()**

> requiere: _DataWit.php_, _Validador.php_

> <span style='color:green'>@params</span>: <b>String</b> opcion
> Este archivo Ajax está presente en la sección de estaciones.
> Su uso es gestionar las consignas y plannings de una estación.

Dependiendo del valor de _opcion_ este podrá: listar consignas, leer consignas,modificar consignas, listar plannings (WIP), leer plannings (WIP) y modificar plannings (WIP).

Al tener varias funciones aun en desarrollo, su código está sujeto a cambios y por lo tanto no se muestra en este documento hasta que esté acabado.

```php
// código no disponible
```

### 5.2.2 **A_Alarmas.php**

> requiere Database.php, Validador.php

> <span style='color:green'>@params</span> <b>String</b> funcion

Este archivo Ajax aparece sólo en la sección de explorador de alarmas (no en el menu Sur) y se encarga de buscar las alarmas de un cliente en función de una serie de filtros. También es capaz de reconocer las alarmas y en algunos casos también puede extraer detalles de estas. Crea el codigo <b>HTML</b> de la sección.

```php
<?php
require_once '../app/Database/Database.php';
require '../app/Models/Validador.php';
$db = new Database();
$vlr = new Validador();

if ($_POST['funcion'] == "actualizar") {
    $nombre = $_POST['nombre'];
    $emp = $_POST['emp'];
    $orden = $_POST['orden'];
    $sentido = $_POST['sentido'];
    $fechaIni = $_POST['fechaInicio'];
    $fechaFin = $_POST['fechaFin'];

    if ($vlr->valFecha($fechaFin)) {
        $idusu = $db->obtenerIdUsuario($nombre, $emp);
        $alarmas = $db->obtenerAlarmasUsuario($idusu, $orden, $sentido, $fechaIni, $fechaFin);
        $alarmasLimpio = array();
        foreach ($alarmas as $estacion => $alarmas) {
            if ($alarmas != false) {
                $alarmasLimpio[$estacion] = $alarmas;
            }
        }
        echo "<tr>
        <th onclick=reordenar('estacion')>Estacion</th>
        <th onclick=reordenar('senal')>Indicador </th>
        <th onclick=reordenar('valor')>Valor de la Indicador</th>
        <th onclick=reordenar('origenfecha')>Fecha de Origen</th>
        <th onclick=reordenar('restauracionfecha')>Fecha de Restauracion</th>
        <th onclick=reordenar('reconusu')>Reconocida por</th>
        <th onclick=reordenar('reconfecha')>Fecha de reconocimiento</th>
        </tr>";
        foreach ($alarmasLimpio as $index => $alarma) {
            switch ($alarma['estado']) {
                case 1:
                    echo "<tr class='activaNo' >";
                    break;
                case 2:
                    echo "<tr class='restNo'>";
                    break;
                case 3:
                    echo "<tr class='activaSi'>";
                    break;
                case 4:
                    echo "<tr class='restSi'>";
                    break;
                default:
                    break;
            }
            foreach ($alarma as $dato => $valor) {
                if ($dato != 'estado' && $dato != 'id_alarmas') {
                    switch ($dato) {
                        case 'valor_alarma':
                            echo "<td>";
                            echo $valor;


                            if (preg_match('~[0-9]+~', $alarma['valor_alarma'])) {
                                echo '<i class="fas fa-chart-bar" style="opacity:100%;color:rgb(1,168,184)" onclick="detallesAlarma(' . $alarma['id_alarmas'] . ')"></i>';
                            }
                            echo "</td>";
                            break;
                        case 'ack_por':
                            if ($valor == null) {
                                echo "<td>";
                                echo '<i class="fas fa-eye" onclick="reconocer(' . $alarma['id_alarmas'] . ')"></i>';
                                echo "</td>";
                            } else {
                                echo "<td>";
                                echo $valor;
                                echo "</td>";
                            }
                            break;
                        default:
                            echo "<td>";
                            echo $valor;
                            echo "</td>";
                            break;
                    }
                }
            }
            echo "</tr>";
        }
    }
    else {
        echo "<p>fechas no validas</p>";
    }
}

if ($_POST['funcion'] == "estacion") {
    $orden = $_POST['orden'];
    $sentido = $_POST['sentido'];
    $fechaIni = $_POST['fechaInicio'];
    $fechaFin = $_POST['fechaFin'];
    $id_estacion = $_POST['estacion'];
    $alarmasEstacion = $db->obtenerAlarmasEstacion($id_estacion, $orden, $sentido, null, null);
    if ($alarmasEstacion != false) {
        $alarmasEstacionLimpio = array();
        foreach ($alarmasEstacion as $alarma => $datos) {
            if ($alarma != false) {
                $alarmasEstacionLimpio[$alarma] = $datos;
            }
        }
        echo "<tr>
            <th onclick=reordenar('estacion')>Estacion</th>
        <th onclick=reordenar('senal')>Indicador</th>
        <th onclick=reordenar('valor')>Valor de Indicador</th>
        <th onclick=reordenar('origenfecha')>Fecha de Origen</th>
        <th onclick=reordenar('restauracionfecha')>Fecha de Restauracion</th>
        <th onclick=reordenar('reconusu')>Reconocida por</th>
        <th onclick=reordenar('reconfecha')>Fecha de reconocimiento</th>
            </tr>";
        foreach ($alarmasEstacionLimpio as $alarma) {

            switch ($alarma['estado']) {
                case 1:
                    echo "<tr class='activaNo' >";

                    break;
                case 2:
                    echo "<tr class='restNo'>";

                    break;
                case 3:
                    echo "<tr class='activaSi'>";

                    break;
                case 4:
                    echo "<tr class='restSi'>";
                    break;

                default:
                    break;
            }
            foreach ($alarma as $dato => $valor) {
                if ($dato != 'estado' && $dato != 'id_alarmas') {
                    switch ($dato) {
                        case 'valor_alarma':
                            echo "<td>";
                            echo $valor;

                            if (preg_match('~[0-9]+~', $alarma['valor_alarma'])) {
                                echo '<i class="fas fa-chart-bar" style="opacity:100%;color:rgb(1,168,184)" onclick="detallesAlarma(' . $alarma['id_alarmas'] . ')"></i>';
                            }
                            echo "</td>";
                            break;
                        case 'ack_por':
                            if ($valor == null) {
                                echo "<td>";
                                echo '<i class="fas fa-eye" onclick="reconocer(' . $alarma['id_alarmas'] . ')"></i>';
                                echo "</td>";
                            } else {
                                echo "<td>";
                                echo $valor;
                                echo "</td>";
                            }
                            break;
                        default:
                            echo "<td>";
                            echo $valor;
                            echo "</td>";
                            break;
                    }
                }
            }
            echo "</tr>";
        }
    }
}

if ($_POST['funcion'] == "reconocer") {
    $nombre = $_POST['nombre'];
    $id_alarma = $_POST['alarma'];
    $hora = date('Y/m/d H:i:s', time());
    $recon = $db->reconocerAlarma($id_alarma, $nombre, $hora);
    if ($recon != false) {
        echo "bien";
    } else {
        echo "fallo al reconocer la alarma";
    }
}
if ($_POST['funcion'] == "detalles") {
    $id = $_POST['id'];
    $detalles = $db->obtenerDetallesAlarma($id);
    if ($detalles != false) {
        echo json_encode($detalles);
    } else {
        echo ' error extrayendo detalles (origen no historizable)';
    }
}

```

### 5.2.3 **A_Conexiones.php**

> requiere Database.php, Validador.php

> <span style='color:green'>@params</span> <b>String</b> opcion

Este archivo complementa la sección de comunicaciones y sus funciones son tan solo buscar las ultimas conexiones de las distintas estaciones que tenga un usuario y algunos detalles mas. Crea el <b>HTML</b> de la sección.

```php
<?php
require_once '../app/Database/Database.php';
require '../app/Models/Validador.php';
$opcion = $_POST['opcion'];
$db = new Database();
if ($opcion == 'conex') {
    $nombre = $_POST['nombre'];
    $pwd = $_POST['pwd'];
    $estaciones = $db->mostrarEstacionesCliente($nombre, $pwd);
    $ultimasConexiones = array();
    foreach ($estaciones as $estacion) {
        $ultimasConexiones[$estacion['nombre_estacion']] = $db->ultimaComunicacionEstacion($estacion['id_estacion']);
    }
    foreach ($ultimasConexiones as $estacion => $datos) {
        foreach ($datos[0] as $dato => $valor) {
            if ($dato == 'valor_date') {
                if ($valor != null) {
                    $ultima = new DateTime;
                    $ultima = DateTime::createFromFormat('Y-m-d H:i:s', $valor);
                    $ahora = new DateTime("now");
                    $dif = $ahora->diff($ultima);
                    $ultimasConexiones[$estacion][0]['estado'] = "correcto";
                    if ($dif->days >= 1) {
                        $ultimasConexiones[$estacion][0]['estado'] = "aviso";
                    }
                    if ($dif->days >= 2) {
                        $ultimasConexiones[$estacion][0]['estado'] = "error";
                    }
                } else {
                    $ultimasConexiones[$estacion][0]['estado'] = "aviso";
                }
            }
        }
    }
    foreach ($ultimasConexiones as $estacion => $datos) {
        echo "<tr id='seccionEstacion' name=" . $datos[0]['id_estacion'] . ">";
        foreach ($datos[0] as $dato => $valor) {
            if ($dato == 'nombre_estacion') {
                echo "<td id='secNombre'>";
                echo $valor;
                echo "</td>";
            }
            if ($dato == 'valor_date') {
                if ($valor != null) {
                    echo "<td id='secUltima'>";
                    echo "Última conexión: " . $valor;
                    echo "</td>";
                } else {
                    echo "<td id='secUltima'>";
                    echo "Última conexión: desconocida";
                    echo "</td>";
                }
            }
            if ($dato == 'nombre_tag') {
            }
            if ($dato == 'id_estacion') {
            }
            if ($dato == 'estado') {
                if ($valor == "correcto") {
                    echo "<td id='secEstado'><i class='fas fa-check'></i></td>";
                }
                if ($valor == "error") {
                    echo "<td id='secProblema' class='' style='color:tomato'><i name='alerta' class='fas fa-exclamation-triangle alerta'></i></td>";
                }
                if ($valor == "aviso") {
                    echo "<td id='secProblema'><i name='alerta' class='fas fa-exclamation-triangle alerta'></i></td>";
                }
            }
        }
        echo "</tr>";
    }
}

if ($opcion == 'nom') {
    $id_estacion = $_POST['estacion'];
    $estacion = $db->obtenerNombreEstacion($id_estacion);
    echo ($estacion[0]['nombre_estacion']);
}

```

### 5.2.4 **A_Estacion.php**

> requiere Database.php

> @ params <b>String</b> opcion, <b>INT</b> id_estacion, <b>String</b> tipo (opcional)

Este archivo se usa en las vistas de estación y dependiendo de la peticion este archivo extrae datos estáticos de estaciones (nombre, ubicación, señales etc) y extrae también valores en tiempo real de estas. Los resultados los devuelve en formato <span style='color:gold'><b>JS</b></span>ON.

```php
<?php
require_once '../app/Database/Database.php';
$DB = new Database();
$opcion = $_POST['opcion'];
$id_estacion = $_POST['estacion'];
$tipo = "";
if (isset($_POST['tipo'])) {
    $tipo = $_POST['tipo'];
}
if ($opcion == 'actualizar' && $tipo == 'todos') {
    try {
        echo json_encode($DB->datosEstacion($id_estacion, true));
    } catch (Throwable $e) {
        echo $e;
    }
}
if ($opcion == 'trends') {
    $datosAnalog = json_decode($_POST['arrTags']);
    $trendsEstacion = [];
    foreach ($datosAnalog as $indexTag => $datosTag) {
        if ($indexTag != null && $datosTag != null) {
            $tag = $datosTag->id_tag;
            $trend = $DB->tagTrend($tag, $id_estacion);
            if ($trend != null || !empty($trend)) {
                $trendFilt = [];
                foreach ($trend as $index => $valores) {
                    foreach ($valores as $nombre => $valor) {
                        if ($valor != null && $nombre != 'fecha') {
                            $trendFilt['max'][] = $valor;
                        }
                        if ($nombre == 'fecha') {
                            $trendFilt['fecha'][] = $valor;
                        }
                    }
                }
                $trendsEstacion[$datosTag->id_tag] = $trendFilt;
            }
        }
    }
    echo json_encode($trendsEstacion);
}
if ($opcion == 'foto') {
    $foto = $DB->obtenerFotoEstacion($id_estacion);
    echo $foto;
}
if ($opcion == 't_trend') {
    $datosAnalog = json_decode($_POST['arrTags']);
    $datosTrends = $DB->tagsTrends($datosAnalog);
    $arr = array();
    $arrTrends = array();
    foreach ($datosTrends as $key => $item) {
        $arr[$item['id_tag']][$key] = $item;
    }
    ksort($arr, SORT_NUMERIC);
    echo json_encode($arr);
}

```

### 5.2.5 **A_Graficas.php**

> requiere Database.php

> <span style='color:green'>@params</span> <b>String</b> opcion, <b>INT</b> id_estacion, <b>INT</b> tag (opcional)

Este archivo se usa en la sección de vista rápida en las gráficas. Recolecta las señales disponibles de las distintas estaciones de un usuario y también los valores de estas y sus calculados en función de varios parámetros. Los resultados los devuelve en formato <span style='color:gold'><b>JS</b></span>ON.

```php
<?php
require_once '../app/Database/Database.php';
if (isset($_POST['tag'])) {
    $id_tag = $_POST['tag'];
}
$opcion = $_POST['opcion'];
$id_estacion = $_POST['estacion'];
$db = new Database();
if ($opcion == "render") {
    $histos = $db->historicosTagEstacion($id_estacion, $id_tag);
    if ($histos != false) {
        echo json_encode($histos);
    } else {
        echo "error";
    }
}
if ($opcion == "tags") {
    $tags = $db->tagsEstacion($id_estacion);
    if ($tags != false) {
        echo json_encode($tags);
    } else {
        echo "error";
    }
}
if ($opcion == "meta") {
    $metaDatos = $db->metaTag($id_tag, $id_estacion);
    if ($metaDatos != false) {
        echo json_encode($metaDatos);
    } else {
        echo "error";
    }
}

```

### 5.2.6 **A_GraficasCustom.php**

> requiere Database.php, Validador.php

> <span style='color:green'>@params</span> <b>String</b> opcion

Este archivo se usa en las sección de vista personalizada en las gráficas. Recolecta las señales disponibles de las distintas estaciones de un usuario y también los valores de estas y sus calculados en función de varios parámetros. También gestiona los presets de los usuarios siendo capaz de leerlos, aplicarlos y modificarlos. Los resultados los devuelve en formato <span style='color:gold'><b>JS</b></span>ON.

```php
<?php
require_once '../app/Database/Database.php';
require '../app/Models/Validador.php';

$db = new Database();
$vlr = new Validador();
$opcion = $_POST['opcion'];
if ($opcion == 'tag') {
    $id_estacion = $_POST['estacion'];
    $id_tag = $_POST['id_tag'];
    $fechaIni = $_POST['fechaIni'];
    $fechaFin = $_POST['fechaFin'];
    $meta = $_POST['meta'];
    $ajustesMeta = explode("/", $meta);
    if ($vlr->valFecha($fechaIni) && $vlr->valFecha($fechaFin)) {
        $info = $db->historicosTagEstacionCustom($id_estacion, $id_tag, $ajustesMeta, $fechaIni, $fechaFin);
        echo json_encode($info);
    } else {
        echo json_encode("fechas no validas");
    }
}
if ($opcion == 'guardar') {
    $datosPreset = json_decode($_POST['arrDatosPreset']);
        $usuario = $datosPreset->usuario;
        $nombre_preset = $datosPreset->nombre;
        $id_estacion = $datosPreset->id_estacion;
        $tags_colores = $datosPreset->tags_colores;
        $resultado = $db->guardarPreset($usuario, $nombre_preset, $id_estacion, $tags_colores);
    echo $resultado;
}
if ($opcion == 'leerPresets') {
    $datos = json_decode($_POST['arrdatos']);
    $n_usuario = $datos->nombre;
    $id_usuario = $db->obtenerIdUsuario($n_usuario);
    if ($id_usuario) {
        $presets = $db->leerPresets($id_usuario);
        if ($_POST['para'] == 'mostrar') {
            $res = "<option value='none'>Sin preset</option>";
            foreach ($presets as $index => $datos) {
                $nombre_preset = substr($datos['configuracion'], 0, strpos($datos['configuracion'], '@'));
                $res .= "<option value='" . $nombre_preset . "'>" . $nombre_preset . "</option>";
            }
            echo $res;
        }
        if ($_POST['para'] == 'cargar') {
            echo json_encode($presets);
        }
    }
}
if ($opcion == 'borrar') {
    $datos = json_decode($_POST['arrdatos']);
    $usuario = $datos->nombre;
    $id_usuario = $db->obtenerIdUsuario($usuario);
    if ($id_usuario) {
        $preset = $_GET['preset'];
        $db->borrarPreset($preset, $id_usuario);
    }
}

```

### 5.2.7 **A_Informes.php**

> requiere Database.php, Koolreport/core/autoload.php, Informecaudales.php, Validador.php

> <span style='color:green'>@params</span> <b>String</b> opcion, <b>TS</b> fechaIni, <b>TS</b> fechaFin, <b>Array</b> nombres

Este archivo se usa en la sección de informes y se encarga de extraer los datos de los informes según una serie de parámetros para despues formatearlos y usar Koolreport y crear el informe.

```php
<?php
require_once '../app/Database/Database.php';
require_once "../app/Libraries/koolreport/core/autoload.php";
require_once '../app/Models/InformeCaudales.php';
require '../app/Models/Validador.php';
use \koolreport\widgets\koolphp\Table;
$db = new Database();
$opcion = $_POST['opcion'];
$fechaIni = $_POST['fechaIni'];
$fechaFin = $_POST['fechaFin'];
$nombres = json_decode(($_POST['arrNombres']));
    if ($opcion == "cau") {
        $estaciones = json_decode(($_POST['arrEstaciones']));
        $informesDep = array();
        $informeDep = array();
        $informeTabla = array(['estacion', 'señal', 'fecha', 'maximo', 'minimo', 'media']);
        foreach ($estaciones as $index => $estacion) {
            $informeDep = $db->informeSeñalEstacion($estacion, 'cau', $fechaIni, $fechaFin);
            if ($informeDep != null && !empty($informeDep)) {
                $informesDep[$estacion] = $informeDep;
                foreach ($informeDep as $señal => $info) {
                    foreach ($info as $index => $datos) {
                        $informeTabla[] = [$nombres[$estacion], $señal, $datos['fecha'], $datos['maximo'], $datos['minimo'], $datos['media']];
                    }
                }
            }
        }
        $informe = new InformeCaudales($informeTabla);
        $informe->run()->render();
        $table = Table::create(array(
            "dataSource" => $informeTabla,
            "sorting" => array(
                "fecha"=>"desc"
            ),
            "columns" => array(
                "fecha" => array(
                    "cssStyle" => "text-align:left"
                ),
                "maximo" => array(
                    "cssStyle" => "text-align:center"
                ),
                "minimo" => array(
                    "cssStyle" => "text-align:center"
                ),
                "media" => array(
                    "cssStyle" => "text-align:center"
                ),
            ),
            "grouping" => array(
                "estacion" => array(
                    "top" => "<td colspan=4 style='background-color:rgb(39,45,79);font-size:120%;color:whitesmoke;'><b>{estacion}:</b></td>",
                ),
                "señal" => array(
                    "calculate" => array(
                        "{max}" => array("max", "maximo"),
                        "{med}" => array("avg", "media"),
                        "{min}" => array("min", "minimo")
                    ),
                    "top" => "<td style='background-color:rgb(1, 168, 184);font-size:100%;color:rgba(56, 56, 56);'><b>{señal}:</b></td>
                <td style='background-color:rgb(1, 168, 184);font-size:100%;color:rgba(56, 56, 56);text-align:center'><b>Maximo:</b></td>
                <td style='background-color:rgb(1, 168, 184);font-size:100%;color:rgba(56, 56, 56);text-align:center'><b>Minimo:</b></td>
                <td style='background-color:rgb(1, 168, 184);font-size:100%;color:rgba(56, 56, 56);text-align:center'><b>Media:</b></td>",
                    "bottom" => function ($val) {
                        $fila = "<td style='background-color:grey;font-size:100%;color:white;'><b>Resumen de " . $val['{señal}'] . ":</b></td>
                   <td style='background-color:grey;font-size:100%;color:white;text-align:center'> Maximo: " . $val['{max}'] . "</td>
                   <td style='background-color:grey;font-size:100%;color:white;text-align:center'> Minimo: " . $val['{min}'] . "</td>
                   <td style='background-color:grey;font-size:100%;color:white;text-align:center'> Media: " . number_format($val['{med}'], 2) . "</td>";
                        return $fila;
                    }
                )
            ),
            "showHeader" => false,
            "cssClass" => array(
                "table" => "table table-hover table-bordered",
            ),
        ));
    }
    if ($opcion == "niv") {
        $estaciones = json_decode(($_POST['arrEstaciones']));
        $informesDep = array();
        $informeDep = array();
        $informeTabla = array(['estacion', 'señal', 'fecha', 'maximo', 'minimo', 'media']);
        foreach ($estaciones as $index => $estacion) {
            // $informesDep[] = $estacion;
            $informeDep = $db->informeSeñalEstacion($estacion, 'niv', $fechaIni, $fechaFin);
            if ($informeDep != null && !empty($informeDep)) {
                $informesDep[$estacion] = $informeDep;
                foreach ($informeDep as $señal => $info) {
                    foreach ($info as $index => $datos) {
                        $informeTabla[] = [$nombres[$estacion], $señal, $datos['fecha'], $datos['maximo'], $datos['minimo'], $datos['media']];
                    }
                }
            }
        }
        $informe = new InformeCaudales($informeTabla);
        $informe->run()->render();
        $table = Table::create(array(
            "dataSource" => $informeTabla,
            "sorting" => array(
                "fecha"=>"desc"
            ),
            "columns" => array(
                "fecha" => array(
                    "cssStyle" => "text-align:center"
                ),
                "maximo" => array(
                    "cssStyle" => "text-align:center"
                ),
                "minimo" => array(
                    "cssStyle" => "text-align:center"
                ),
                "media" => array(
                    "cssStyle" => "text-align:center"
                ),
            ),
            "grouping" => array(
                "estacion" => array(
                    "top" => "<td colspan=4 style='background-color:rgb(39,45,79);font-size:120%;color:whitesmoke;'><b>{estacion}:</b></td>",
                ),
                "señal" => array(
                    "calculate" => array(
                        "{max}" => array("max", "maximo"),
                        "{med}" => array("avg", "media"),
                        "{min}" => array("min", "minimo")
                    ),
                    "top" => "<td style='background-color:rgb(1, 168, 184);font-size:100%;color:rgba(56, 56, 56);'><b>{señal}:</b></td>
                <td style='background-color:rgb(1, 168, 184);font-size:100%;color:rgba(56, 56, 56);text-align:center'><b>Maximo:</b></td>
                <td style='background-color:rgb(1, 168, 184);font-size:100%;color:rgba(56, 56, 56);text-align:center'><b>Minimo:</b></td>
                <td style='background-color:rgb(1, 168, 184);font-size:100%;color:rgba(56, 56, 56);text-align:center'><b>Media:</b></td>",
                    "bottom" => "<td style='background-color:grey;font-size:100%;color:white;'><b>Resumen de {señal}:</b></td>
                <td style='background-color:grey;font-size:100%;color:white;text-align:center'> Maximo: {max}</td>
                <td style='background-color:grey;font-size:100%;color:white;text-align:center'> Minimo: {min}</td>
                <td style='background-color:grey;font-size:100%;color:white;text-align:center'> Media: {med}</td>",
                )
            ),
            "showHeader" => false,
            "cssClass" => array(
                "table" => "table table-hover table-bordered",
            ),
        ));
    }
    if ($opcion == "acu") {
        $estaciones = json_decode(($_POST['arrEstaciones']));
        $informesDep = array();
        $informeDep = array();
        $informeTabla = array(['estacion', 'señal', 'fecha', 'valor']);
        foreach ($estaciones as $index => $estacion) {
            $informeDep = $db->informeSeñalEstacion($estacion, 'acu', $fechaIni, $fechaFin);
            if ($informeDep != null && !empty($informeDep)) {
                $informesDep[$estacion] = $informeDep;
                foreach ($informeDep as $señal => $info) {
                    foreach ($info as $index => $datos) {
                        $informeTabla[] = [$nombres[$estacion], $señal, $datos['fecha'], $datos['valor']];
                    }
                }
            }
        }
        $informe = new InformeCaudales($informeTabla);
        $informe->run()->render();
        $table = Table::create(array(
            "dataSource" => $informeTabla,
            "sorting" => array(
                "fecha"=>"desc"
            ),
            "columns" => array(

                "fecha" => array(
                    "cssStyle" => "text-align:left"
                ),
                "valor" => array(
                    "cssStyle" => "text-align:center"
                ),
            ),
            "grouping" => array(
                "estacion" => array(
                    "top" => "<td colspan=4 style='background-color:rgb(39,45,79);font-size:120%;color:whitesmoke;'><b>{estacion}:</b></td>",
                ),
                "señal" => array(
                    "calculate" => array(
                        "{maxi}" => array("sum", "valor"),
                    ),
                    "top" => "<td style='background-color:rgb(1, 168, 184);font-size:100%;color:rgba(56, 56, 56);text-align:left'><b>{señal}:</b></td>
                            <td style='background-color:rgb(1, 168, 184);font-size:100%;color:rgba(56, 56, 56);text-align:center'><b>acumulado:</b></td>",

                    "bottom" => "<td  style='background-color:grey;font-size:100%;color:white;'><b>Total de {señal}:</b></td>
                            <td style='background-color:grey;font-size:100%;color:white;text-align:center'><b>{maxi}</b></td>",
                )
            ),
            "showHeader" => false,
            "cssClass" => array(
                "table" => "table table-hover table-bordered",
            ),
        ));
    }
    if ($opcion == "clo") {
        $estaciones = json_decode(($_POST['arrEstaciones']));
        $informesDep = array();
        $informeDep = array();
        $informeTabla = array(['estacion', 'señal', 'fecha', 'maximo', 'minimo', 'media']);
        foreach ($estaciones as $index => $estacion) {
            $informeDep = $db->informeSeñalEstacion($estacion, 'clo', $fechaIni, $fechaFin);
            if ($informeDep != null && !empty($informeDep)) {
                $informesDep[$estacion] = $informeDep;
                foreach ($informeDep as $señal => $info) {
                    foreach ($info as $index => $datos) {
                        $informeTabla[] = [$nombres[$estacion], $señal, $datos['fecha'], $datos['maximo'], $datos['minimo'], $datos['media']];
                    }
                }
            }
        }
        $informe = new InformeCaudales($informeTabla);
        $informe->run()->render();
        $table = Table::create(array(
            "dataSource" => $informeTabla,
            "sorting" => array(
                "fecha"=>"desc"
            ),
            "columns" => array(
                "fecha" => array(
                    "cssStyle" => "text-align:center"
                ),
                "maximo" => array(
                    "cssStyle" => "text-align:center"
                ),
                "minimo" => array(
                    "cssStyle" => "text-align:center"
                ),
                "media" => array(
                    "cssStyle" => "text-align:center"
                ),
            ),
            "grouping" => array(
                "estacion" => array(
                    "top" => "<td colspan=4 style='background-color:rgb(39,45,79);font-size:120%;color:whitesmoke;'><b>{estacion}:</b></td>",
                ),
                "señal" => array(
                    "calculate" => array(
                        "{max}" => array("max", "maximo"),
                        "{med}" => array("avg", "media"),
                        "{min}" => array("min", "minimo")
                    ),
                    "top" => "<td style='background-color:rgb(1, 168, 184);font-size:100%;color:rgba(56, 56, 56);'><b>{señal}:</b></td>
                <td style='background-color:rgb(1, 168, 184);font-size:100%;color:rgba(56, 56, 56);text-align:center'><b>Maximo:</b></td>
                <td style='background-color:rgb(1, 168, 184);font-size:100%;color:rgba(56, 56, 56);text-align:center'><b>Minimo:</b></td>
                <td style='background-color:rgb(1, 168, 184);font-size:100%;color:rgba(56, 56, 56);text-align:center'><b>Media:</b></td>",
                    "bottom" => "<td style='background-color:grey;font-size:100%;color:white;'><b>Resumen de {señal}:</b></td>
                <td style='background-color:grey;font-size:100%;color:white;text-align:center'> Maximo: {max}</td>
                <td style='background-color:grey;font-size:100%;color:white;text-align:center'> Minimo: {min}</td>
                <td style='background-color:grey;font-size:100%;color:white;text-align:center'> Media: {med}</td>",
                )
            ),
            "showHeader" => false,
            "cssClass" => array(
                "table" => "table table-hover table-bordered",
            ),
        ));
    }
```

### 5.2.8 **A_Principal.php**

> requiere Database.php

> <span style='color:green'>@params</span> <b>String</b> opcion

Este es el archivo que acompaña a la vista principal.php y gestiona los feeds de las señales que lee como las configuradas por el usuario. También puede leer las configuraciones y modificarlas. Los resultados los devuelve en formato <span style='color:gold'><b>JS</b></span>ON.

### 5.2.9 **A_Reloj.php** --> obsoleta

> requiere Void

> <span style='color:green'>@params</span> Void

Esta sección se cargaba desde el inicio y comprobaba el estado de la sesión en servidor para calcular tiempos fuera de la página. Ya no está en uso y depende del cliente.

### 5.2.10 **A_Sur.php**

> requiere Database.php

> <span style='color:green'>@params</span> <b>String</b> caso

Este archivo es el que gestiona el menú sur de alarmas. Dependiendo de la sección en la que se encuentre, extrae las alarmas que aparecen en el menú sur creando el código <b>HTML</b>.
