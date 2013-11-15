<?php

namespace DJP\Model\Backend\ConfSubject\DataHandler;

class QueryHandler 
{
    private $db;
    
    public function __construct()
    {
        $this->db = \DJP\Services\Registry::getInstance()->getEntry("db");
    }
    
	/**
	*	Liste aller Fächer aus der Datenbank auslesen
	**/
    public function getSubjectList()
    {
        $this->db->setStatement("SELECT * FROM t:faecher ");
        return $this->db->pselect();
    }
    
	/**
	*	Fach anhand einer ID aus der Datenbank auslesen
	**/
    public function getSubjectById($id)
    {
        $this->db->setStatement("SELECT * FROM t:faecher WHERE Fach_Id = :id ");
        $this->db->bindParameter("id", "i", $id);
        return $this->db->pselect1();
    }
}