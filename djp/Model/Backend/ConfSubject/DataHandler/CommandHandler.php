<?php

namespace DJP\Model\Backend\ConfSubject\DataHandler;

class CommandHandler
{

    private $db;

    public function __construct()
    {
        $this->db = \DJP\Services\Registry::getInstance()->getEntry("db");
    }

    public function updateSubjectById($id, $array)
    {
        $this->db->setStatement("UPDATE t:Faecher SET Kuerzel = :kuerzel, Name = :name WHERE Fach_Id = :id ");
		$this->db->bindParameter("id", "i", $id);
		$this->db->bindParameter("kuerzel", "s", $array["kuerzel"]);
		$this->db->bindParameter("name", "s", $array["name"]);

        return $this->db->pdbquery();
    }

    public function addSubject($array)
    {
		$this->db->setStatement("INSERT INTO t:Faecher (Kuerzel, Name) VALUES (:kuerzel, :name) ");
		$this->db->bindParameter("kuerzel", "s", $array["kuerzel"]);
		$this->db->bindParameter("name", "s", $array["name"]);

        return $this->db->pdbquery();
    }

    public function deleteSubjectById($id)
    {
		$this->db->setStatement("DELETE FROM t:Faecher WHERE Fach_Id = :id ");
		$this->db->bindParameter("id", "i", $id);
        return $this->db->pdbquery();
    }

}