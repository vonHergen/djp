<?php

namespace DJP\Model\Backend\ConfEducation\DataHandler;

class QueryHandler 
{
    private $db;
    
    public function __construct()
    {
        $this->db = \DJP\Services\Registry::getInstance()->getEntry("db");
    }
    
    public function getEducationList()
    {
        $this->db->setStatement("SELECT * FROM t:bildungsgaenge ");
        return $this->db->pselect();
    }
    
    public function getEducationById($id)
    {
        $this->db->setStatement("SELECT * FROM t:bildungsgaenge WHERE Bildungsgang_Id = :id ");
        $this->db->bindParameter("id", "i", $id);
        return $this->db->pselect1();
    }
}