<?php

namespace DJP\Model\Backend\ConfLfield\DataHandler;

class QueryHandler 
{
    private $db;
    
    public function __construct()
    {
        $this->db = \DJP\Services\Registry::getInstance()->getEntry("db");
    }
    
    public function getLfieldList()
    {
        $this->db->setStatement("SELECT * FROM t:Lernfelder ");
        return $this->db->pselect();
    }
    
    public function getLfieldById($id)
    {
        $this->db->setStatement("SELECT * FROM t:Lernfelder WHERE Lernfeld_Id = :id ");
        $this->db->bindParameter("id", "i", $id);
        return $this->db->pselect1();
    }
}