<?php

namespace DJP\Model\Backend\ConfUser\DataHandler;

class CommandHandler
{

    private $db;

    /**
     * Datenbankobjekt aus der Registry laden
     */
    public function __construct()
    {
        $this->db = \DJP\Services\Registry::getInstance()->getEntry("db");
    }

    /**
     * Einen bestehenden Benutzer, anhand der ID, in der Datenbank aktualisieren.
     * 
     * @param int $id
     * @param array $array
     * @return bool
     */
    public function updateUserById($id, $array)
    {
        if (!empty($array["password"])) {
            $this->db->setStatement("UPDATE t:benutzer SET Vorname = :vorname, Nachname = :nachname, Email = :email, Passwort = :pass, Benutzer_Typ = :role, Bildungsgang_Id = :education WHERE Benutzer_Id = :id ");
            $this->db->bindParameter("pass", "s", trim(md5($array["password"])));
        }
        else {
            $this->db->setStatement("UPDATE t:benutzer SET Vorname = :vorname, Nachname = :nachname, Email = :email, Benutzer_Typ = :role, Bildungsgang_Id = :education WHERE Benutzer_Id = :id ");
        }
        $this->db->bindParameter("id", "i", $id);
        $this->db->bindParameter("vorname", "s", $array["vorname"]);
        $this->db->bindParameter("nachname", "s", $array["nachname"]);
        $this->db->bindParameter("email", "s", $array["email"]);
        $this->db->bindParameter("role", "i", $array["role"]);
        $this->db->bindParameter("education", "i", $array["education"]);

        return $this->db->pdbquery();
    }

    /**
     * Einen neuen Benutzer in der Datenbank speichern.
     * 
     * @param array $array
     * @return bool
     */
    public function addUser($array)
    {

        $this->db->setStatement("INSERT INTO t:benutzer (Vorname, Nachname, Email, Passwort, Benutzer_Typ, Bildungsgang_Id) VALUES (:vorname, :nachname, :email, :pass, :role, :education)");

        $this->db->bindParameter("vorname", "s", $array["vorname"]);
        $this->db->bindParameter("nachname", "s", $array["nachname"]);
        $this->db->bindParameter("email", "s", $array["email"]);
        $this->db->bindParameter("role", "i", $array["role"]);
        $this->db->bindParameter("pass", "s", trim(md5($array["password"])));
        $this->db->bindParameter("education", "i", $array["education"]);

        return $this->db->pdbquery();
    }

    /**
     * Einen bestehenden Benutzer, anhand der ID, aus der Datenbank loeschen.
     * 
     * @param int $id
     * @return bool
     */
    public function deleteUserById($id)
    {
        $this->db->setStatement("DELETE FROM t:benutzer WHERE Benutzer_Id = :id ");
        $this->db->bindParameter("id", "i", $id);
        return $this->db->pdbquery();
    }

}