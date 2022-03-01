<?php

require(APPPATH . "Database/Database.php");


class Usuario
{

    private $nombre;
    private $contrasena;
    private $cliente;
    private $DB;


    public function __construct($nombre, $contrasena)
    {
        $this->nombre = $nombre;
        $this->contrasena = $contrasena;


        //falta: cambiar a nueva BD
        $this->DB = new Database($this->nombre, $this->contrasena);
    }

    public function existeUsuario()
    {
        try {
            return $this->DB->existeUsuario($this->nombre, $this->contrasena);
        } catch (\Throwable $th) {
        }
    }

    public function obtenerEstacionesUsuario()
    {
        try {
            return $this->DB->mostrarEstacionesCliente($this->nombre, $this->contrasena);
        } catch (Throwable $e) {
            return false;
        }
    }

    public function obtenerUltimaInfoEstacion($id_estacion)
    {
        try {
            return $this->DB->datosEstacion($id_estacion, true);
        } catch (Throwable $e) {
            return $e;
        }
    }

    public function obtenerHistoricosEstacion($id_estacion, $fechaInicio, $fechaFinal)
    {
        if ($fechaFinal == null) {
            $fechaFinal = "";
        } else if ($fechaInicio == null) {
            $fechaInicio = "";
        }
        try {
            return $this->DB->historicosEstacion($id_estacion, $fechaInicio, $fechaFinal);
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function obtenerTagsEstaciones()
    {

        $estaciones = $this->obtenerEstacionesUsuario();
        $tagsEstacion = array();
        foreach ($estaciones as $estacion) {
            $id_estacion = $estacion['id_estacion'];
            $tagsEstacion[$estacion['id_estacion']] = $this->DB->tagsEstacion($id_estacion);
        }
        return $tagsEstacion;
    }

    public function ultimasConexiones()
    {

        $estaciones = $this->obtenerEstacionesUsuario();
        if ($estaciones != false) {
            $ultimasConexiones = array();
            foreach ($estaciones as $index => $estacion) {
                $ultimasConexiones[$estacion['nombre_estacion']] = $this->DB->ultimaComunicacionEstacion($estacion['id_estacion']);
            }
            foreach ($ultimasConexiones as $estacion => $datos) {
                foreach ($datos[0] as $dato => $valor) {
                    if ($dato == 'valor_date') {
                        $ultima = new DateTime;
                        $ultima = DateTime::createFromFormat('Y-m-d H:i:s', $valor);
                        $ahora = new DateTime("now");
                        $dif = $ahora->diff($ultima);
                        if ($dif->days >= 1) {
                            $ultimasConexiones[$estacion][0]['estado'] = "error";
                        } else {
                            $ultimasConexiones[$estacion][0]['estado'] = "correcto";
                        }
                    }
                }
            }
            return $ultimasConexiones;
        }
    }

    public function ultimaConexionEstacion($id_estacion)
    {
        $ultimaConex = $this->DB->ultimaComunicacionEstacion($id_estacion);
        if ($ultimaConex != false) {
            return $ultimaConex;
        }
        return false;
    }

    

    /**
     * Get the value of contrasena
     */
    public function getContrasena()
    {
        return $this->contrasena;
    }
    /**
     * Set the value of contrasena
     *
     * @return  self
     */
    public function setContrasena($contrasena)
    {
        $this->contrasena = $contrasena;
        return $this;
    }
    /**
     * Get the value of nombre
     */
    public function getNombre()
    {
        return $this->nombre;
    }
    /**
     * Set the value of nombre
     *
     * @return  self
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
        return $this;
    }

    /**
     * Get the value of cliente
     */
    public function getCliente()
    {
        return $this->DB->obtenerClienteUsuario($this->nombre, $this->contrasena);
    }

    /**
     * Set the value of cliente
     *
     * @return  self
     */
    public function setCliente($cliente)
    {
        $this->cliente = $cliente;

        return $this;
    }
}
