<?php
// EXPERIMENTAL: VALIDA INPUTS DE LAS SECCIONES HECHOS POR EL USUARIO
class Validador
{
    private $diccionarioTexto;
    private $diccionarioNum;
    private $diccionarioEspecial;
    private $diccionarioEspecialMin;

    public function __construct()
    {
        $this->diccionarioTexto = array(
            "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "z", "ñ"
        );
        $this->diccionarioNum = array(
            "1", "2", "3", "4", "5", "6", "7", "8", "9", "0"
        );
        $this->diccionarioEspecial = array(
            ",", "'", "?", "!", "_", "-", "<", ">", "[", "\\", "/", "¿", "¡", ":", "'", '"', "`", "{", "}", "=", "@", "#", "$", ",%", "&", "º"
        );
        $this->diccionarioExpecialMin = array(
            ",", "'", "?", "!", " ", "_", "<", ">", "[", "\\", "¿", "¡", ":", "'", '"', "`", "{", "}", "=", "@", "#", "$", ",%", "&", "º"
        );
        if (!function_exists('str_constains')) {
            function str_tiene($pajar, $aguja)
            {
                return $aguja !== '' && mb_strpos($pajar, $aguja) !== false;
            }
        }
    }
    public function limpiar($elem)
    {
        $elem =  str_replace(' ', '-', $elem);
        return preg_replace('/[^A-Za-z0-9\-]/', '', $elem);
    }
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
    public function valNum($num)
    {
        $num = "" . $num . "";
        $dic = array_merge($this->diccionarioTexto, $this->diccionarioEspecial);
        for ($i = 0; $i < sizeof($dic); $i++) {
            if (str_tiene($num, $dic[$i])) {
                return false;
            }
        }
        return true;
    }
    public function valLog($log)
    {
        $log = "" . $log . "";
        $dic = $this->diccionarioEspecial;
        for ($i = 0; $i < sizeof($dic); $i++) {
            if (str_tiene($log, $dic[$i])) {
                return false;
            }
        }
        return true;
    }
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
}
