<?php

require(APPPATH . "Database/Database.php");
class Contras
{
    const HASH = PASSWORD_DEFAULT;
    const COST = 14;

    // Almacenamiento de datos del usuario:
    public $uData;
    private $db = new Database();
    // Constructor simulado:
    public function __construct($id)
    {
        $this->uData = $this->db->userData($id);
    }
    // Funcionalidad de guardar los datos simulada:
    public function save()
    {
        $this->db->updateUserData($this->uData[0]['id_usuario'], $$this->uData[0]['id_usuario']['password']);
    }

    public function loginUsuario($password)
    {
        // echo "Login: ", $this->uData[0]['hash'], "\n";
        if (password_verify($password, $this->uData[0]['hash'])) {
            if (password_needs_rehash($this->uData[0]['hash'], self::HASH, ['cost' => self::COST])) {
                $this->setPassword($password);
                $this->save();
            }
            return true;
            //volver a controlador-->exito
        }
        return false;
        //volver a controlador-->error
    }
    public function setPassword($password)
    {
        $this->uData[0]['hash'] = password_hash($password, self::HASH, ['cost' => self::COST]);
    }
}