<?php

namespace DJP\Model\Backend\ConfUser\DataHandler;

class QueryHandler 
{
    private $db;
    
    /**
     * Datenbankobjekt aus der Registry laden.
     */
    public function __construct()
    {
        $this->db = \DJP\Services\Registry::getInstance()->getEntry("db");
    }
    
    /**
     * Alle Benutzer aus der Datenbank laden.
     * 
     * @return array
     */
    public function getUserList()
    {
        $this->db->setStatement("SELECT * FROM t:benutzer b, t:benutzer_typen bt WHERE b.Benutzer_Typ = bt.Typ_id ORDER BY Nachname ASC ");
        return $this->db->pselect();
    }
    
    /**
     * Laden eines gezielten Benutzers anhand der ID.
     * 
     * @param int $id
     * @return array
     */
    public function getUserById($id)
    {
        $this->db->setStatement("SELECT * FROM t:benutzer WHERE Benutzer_Id = :id ");
        $this->db->bindParameter("id", "i", $id);
        return $this->db->pselect1();
    }
    
    /**
     * Alle verfügbaren Benutzerrollen laden.
     * 
     * @return array
     */
    public function getUserRoles()
    {
        $this->db->setStatement("SELECT * FROM t:benutzer_typen ");
        return $this->db->pselect();
    }
    
    /**
     * Einen bestehenden Benutzer anhand der E-Mail laden.
     * 
     * @param string $email
     * @return array
     */
    public function getUserByEmail($email)
    {
        $this->db->setStatement("SELECT * FROM t:benutzer WHERE Email = :email ");
        $this->db->bindParameter("email", "s", $email);
        return $this->db->pselect1();
    }
    
    /**
     * Alle bestehenden Bildungsgänge werden geladen.
     * 
     * @return array
     */
    public function getEducationList()
    {
        $this->db->setStatement("SELECT * FROM t:bildungsgaenge ");
        return $this->db->pselect();
    }
}