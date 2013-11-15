<?php

namespace DJP\Model\Backend\ConfSubject\DataHandler;

class QueryHandler 
{
    private $db;
    
    public function __construct()
    {
        $this->db = \DJP\Services\Registry::getInstance()->getEntry("db");
    }
    
    public function getSubjectList()
    {
        $this->db->setStatement("SELECT * FROM t:Faecher ");
        return $this->db->pselect();
    }
    
    public function getSubjectById($id)
    {
        $this->db->setStatement("SELECT * FROM t:Faecher WHERE Fach_Id = :id ");
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