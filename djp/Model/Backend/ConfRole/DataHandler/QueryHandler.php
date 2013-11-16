<?php

namespace DJP\Model\Backend\ConfRole\DataHandler;

class QueryHandler
{

    private $db;

    public function __construct()
    {
        $this->db = \DJP\Services\Registry::getInstance()->getEntry("db");
    }

    /**
     * 	Alle Bildungsgaenge aus der Datenbank abfragen
     * */
    public function getRoleList()
    {
        $this->db->setStatement("SELECT * FROM t:benutzer_typen ");
        return $this->db->pselect();
    }

    /**
     * 	Einen Bildungsgang anhand einer ID aus der Datenbank auslesen
     */
    public function getRoleById($id)
    {
        $this->db->setStatement("SELECT * FROM t:benutzer_typen WHERE Typ_Id = :id ");
        $this->db->bindParameter("id", "i", $id);
        return $this->db->pselect1();
    }

}