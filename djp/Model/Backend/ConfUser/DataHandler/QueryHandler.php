<?php

namespace DJP\Model\Backend\ConfUser\DataHandler;

class QueryHandler 
{
    private $db;
    
    public function __construct()
    {
        $this->db = \DJP\Services\Registry::getInstance()->getEntry("db");
    }
    
    public function getUserList()
    {
        $this->db->setStatement("SELECT * FROM t:Benutzer b, t:Benutzer_Typen bt WHERE b.Benutzer_Typ = bt.Typ_id ");
        return $this->db->pselect();
    }
    
    public function getUserById($id)
    {
        $this->db->setStatement("SELECT * FROM t:Benutzer WHERE Benutzer_Id = :id ");
        $this->db->bindParameter("id", "i", $id);
        return $this->db->pselect1();
    }
    
    public function getUserRoles()
    {
        $this->db->setStatement("SELECT * FROM t:Benutzer_Typen ");
        return $this->db->pselect();
    }
    
    public function getUserByEmail($email)
    {
        $this->db->setStatement("SELECT * FROM t:Benutzer WHERE Email = :email ");
        $this->db->bindParameter("email", "s", $email);
        return $this->db->pselect1();
    }
}