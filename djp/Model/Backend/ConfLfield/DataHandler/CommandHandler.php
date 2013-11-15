<?php

namespace DJP\Model\Backend\ConfLfield\DataHandler;

class CommandHandler
{

    private $db;

    public function __construct()
    {
        $this->db = \DJP\Services\Registry::getInstance()->getEntry("db");
    }

    public function updateLfieldById($id, $array)
    {
        $this->db->setStatement("UPDATE t:Lernfelder SET Name = :name, Beschreibung = :beschreibung WHERE Lernfeld_Id = :id");
		$this->db->bindParameter("id", "i", $id);
        $this->db->bindParameter("name", "s", $array["name"]);
        $this->db->bindParameter("beschreibung", "s", $array["beschreibung"]);

        return $this->db->pdbquery();
    }

    public function addLfield($array)
    {
		$this->db->setStatement("INSERT INTO t:Lernfelder (name, beschreibung) VALUES (:name, :beschreibung) ");
      
		$this->db->bindParameter("name", "s", $array["name"]);
        $this->db->bindParameter("beschreibung", "s", $array["beschreibung"]);
        return $this->db->pdbquery();
    }

    public function deleteLfieldById($id)
    {
        $this->db->setStatement("DELETE FROM t:Lernfelder WHERE Lernfeld_Id = :id ");
		$this->db->bindParameter("id", "i", $id);
        return $this->db->pdbquery();
    }

}