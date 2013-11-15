<?php

namespace DJP\Model\Backend\ConfSubject\DataHandler;

class CommandHandler
{

    private $db;

    public function __construct()
    {
        $this->db = \DJP\Services\Registry::getInstance()->getEntry("db");
    }

    /**
     * 	Fach anhand einer ID aus der Datenbank auslesen
     */
    public function updateSubjectById($id, $array)
    {
        $this->db->setStatement("UPDATE t:faecher SET Kuerzel = :kuerzel, Name = :name WHERE Fach_Id = :id ");
        $this->db->bindParameter("id", "i", $id);
        $this->db->bindParameter("kuerzel", "s", $array["kuerzel"]);
        $this->db->bindParameter("name", "s", $array["name"]);

        return $this->db->pdbquery();
    }

    /**
     * 	Neues Fach in die Datenbank schreiben
     */
    public function addSubject($array)
    {
        $this->db->setStatement("INSERT INTO t:faecher (Kuerzel, Name) VALUES (:kuerzel, :name) ");
        $this->db->bindParameter("kuerzel", "s", $array["kuerzel"]);
        $this->db->bindParameter("name", "s", $array["name"]);

        return $this->db->pdbquery();
    }

    /**
     * 	Fach anhand einer ID aus der Datenbank loeschen
     */
    public function deleteSubjectById($id)
    {
        $this->db->setStatement("DELETE FROM t:faecher WHERE Fach_Id = :id ");
        $this->db->bindParameter("id", "i", $id);
        return $this->db->pdbquery();
    }

}