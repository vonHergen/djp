<?php

namespace DJP\Model\Backend\ConfEducation\DataHandler;

class CommandHandler
{

    private $db;

    public function __construct()
    {
        $this->db = \DJP\Services\Registry::getInstance()->getEntry("db");
    }

    public function updateEducationById($id, $array)
    {
        $this->db->setStatement("UPDATE t:bildungsgaenge SET Name = :name, Beschreibung = :beschreibung WHERE Bildungsgang_Id = :id");
		$this->db->bindParameter("id", "i", $id);
        $this->db->bindParameter("name", "s", $array["name"]);
        $this->db->bindParameter("beschreibung", "s", $array["beschreibung"]);

        return $this->db->pdbquery();
    }

    public function addEducation($array)
    {
		$this->db->setStatement("INSERT INTO t:bildungsgaenge (Name, Beschreibung) VALUES (:name, :beschreibung) ");
      
		$this->db->bindParameter("name", "s", $array["name"]);
        $this->db->bindParameter("beschreibung", "s", $array["beschreibung"]);
        return $this->db->pdbquery();
    }

    public function deleteEducationById($id)
    {
        $this->db->setStatement("DELETE FROM t:bildungsgaenge WHERE Bildungsgang_Id = :id ");
		$this->db->bindParameter("id", "i", $id);
        return $this->db->pdbquery();
    }

}