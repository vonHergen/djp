<?php

namespace DJP\Model\Backend\ConfUser\DataHandler;

class CommandHandler
{

    private $db;

    public function __construct()
    {
        $this->db = \DJP\Services\Registry::getInstance()->getEntry("db");
    }

    public function updateUserById($id, $array)
    {
        if (!empty($array["password"])) {
            $this->db->setStatement("UPDATE t:Benutzer SET Vorname = :vorname, Nachname = :nachname, Email = :email, Passwort = :pass, Benutzer_Typ = :role WHERE Benutzer_Id = :id ");
            $this->db->bindParameter("pass", "s", trim(md5($array["password"])));
        }
        else {
            $this->db->setStatement("UPDATE t:Benutzer SET Vorname = :vorname, Nachname = :nachname, Email = :email, Benutzer_Typ = :role WHERE Benutzer_Id = :id ");
        }
        $this->db->bindParameter("id", "i", $id);
        $this->db->bindParameter("vorname", "s", $array["vorname"]);
        $this->db->bindParameter("nachname", "s", $array["nachname"]);
        $this->db->bindParameter("email", "s", $array["email"]);
        $this->db->bindParameter("role", "i", $array["role"]);

        return $this->db->pdbquery();
    }

    public function addUser($array)
    {

        $this->db->setStatement("INSERT INTO t:Benutzer (Vorname, Nachname, Email, Passwort, Benutzer_Typ) VALUES (:vorname, :nachname, :email, :pass, :role)");

        $this->db->bindParameter("vorname", "s", $array["vorname"]);
        $this->db->bindParameter("nachname", "s", $array["nachname"]);
        $this->db->bindParameter("email", "s", $array["email"]);
        $this->db->bindParameter("role", "i", $array["role"]);
        $this->db->bindParameter("pass", "s", trim(md5($array["password"])));

        return $this->db->pdbquery();
    }

    public function deleteUserById($id)
    {
        $this->db->setStatement("DELETE FROM t:Benutzer WHERE Benutzer_Id = :id ");
        $this->db->bindParameter("id", "i", $id);
        return $this->db->pdbquery();
    }

}