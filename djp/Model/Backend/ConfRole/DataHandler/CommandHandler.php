<?php

namespace DJP\Model\Backend\ConfRole\DataHandler;

class CommandHandler
{

    private $db;

    public function __construct()
    {
        $this->db = \DJP\Services\Registry::getInstance()->getEntry("db");
    }

    /**
     * 	Bildungsgang anhand einer ID in der Datenbank aktualisieren
     * */
    public function updateRoleById($id, $array)
    {
        $this->db->setStatement("UPDATE t:benutzer_typen SET Name = :name WHERE Typ_Id = :id");
        $this->db->bindParameter("id", "i", $id);
        $this->db->bindParameter("name", "s", $array["name"]);

        return $this->db->pdbquery();
    }

    /**
     * Neuen Bildungsgang in die Datenbank eintragen
     * */
    public function addRole($array)
    {
        $this->db->setStatement("INSERT INTO t:benutzer_typen (Name) VALUES (:name) ");

        $this->db->bindParameter("name", "s", $array["name"]);
        return $this->db->pdbquery();
    }

    /**
     * Bildungsgang anhand einer ID aus der Datenbank loeschen
     * */
    public function deleteRoleById($id)
    {
        $this->db->setStatement("DELETE FROM t:benutzer_typen WHERE Typ_Id = :id ");
        $this->db->bindParameter("id", "i", $id);
        return $this->db->pdbquery();
    }

}