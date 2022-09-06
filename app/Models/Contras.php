<?php
class Contras
{
    const HASH = PASSWORD_DEFAULT;
    const COST = 14;
    // Almacenamiento de datos del usuario:
    public $uData;
    private $db;
    // Constructor simulado:
    public function __construct($id)
    {
        $this->db = new Database();
        $this->uData = $this->db->userData($id);
    }
    // Funcionalidad de guardar los datos simulada:
    public function save()
    {
        $this->db->updateUserData($this->uData[0]['id_usuario'], $this->uData[0]['id_usuario']['password']);
    }
    public function loginUsuario($password)
    {
        if (password_verify($password, $this->uData[0]['hash'])) {
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
    public function setPassword($password)
    {
        $this->uData[0]['hash'] = password_hash($password, self::HASH, ['cost' => self::COST]);
    }
    public function hashear($pwd){
        return password_hash($pwd, self::HASH, ['cost' => self::COST]);
    }
}