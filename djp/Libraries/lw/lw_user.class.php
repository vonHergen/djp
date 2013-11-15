<?php

/**
 * Basierend auf der original Klasse lw_user der Logic-Works GmbH,
 * angepasst fuer EVA.
 */
/* * ************************************************************************
 *  Copyright notice
 *
 *  Copyright 1998-2009 Logic Works GmbH
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *  http://www.apache.org/licenses/LICENSE-2.0
 *  
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 *  
 * ************************************************************************* */

/**
 * Die "lw_user" Klasse liefert der Authentifizierungsklasse die 
 * entsprechenden Userdaten
 * 
 * @package  Framework
 * @author   Dr. Andreas Eckhoff
 * @version  3.0 (beta)
 * @since    PHP 5.0
 */
class lw_user extends lw_object
{

    /**
     * beinhaltet den Inhalt der Konfiguration
     * @var array
     */
    private $config;

    /**
     * beinhaltet den Tabellennamen der Usertabelle
     * @var array
     */
    private $table;

    /**
     * beinhaltet den Tabellennamen der Project_Itemtabelle
     * @var array
     */
    private $atable;

    /**
     * beinhaltet den Tabellennamen der Rollentabelle
     * @var array
     */
    private $rtable;

    /**
     * beinhaltet des Datenbankobjektes
     * @var array
     */
    private $db;

    /**
     * beinhaltet das Ergebnisarray der Userabfrage
     * @var array
     */
    private $result;

    /**
     * hier werden die Grundvariablen gesetzt und der Klasse zur Verf�gung gestellt.
     * Weiterhin werden alle relevanten Objekte aus der Registry der Klasse zur Verf�gung gestellt.
     */
    function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * L�dt die Benutzerdaten aus der Usertabelle anhand der Login/Passwort Kombination
     *
     * @param   string
     * @param   string
     * @return  array
     */
    public function getUserdataByLogin($name, $pass, $type = false)
    {
        $this->db->setStatement('SELECT * FROM t:Benutzer WHERE Email = :email AND Passwort = :pass ');
        $this->db->bindParameter('email', 's', $name);
        $this->db->bindParameter('pass', 's', $pass);
        #die($this->db->prepare());
        $this->result = $this->db->pselect1();

        if (count($this->result) > 0) {
            return $this->result;
        }
        else {
            return false;
        }
    }

    /**
     * Gibt einen Wert aus dem result-Array anhand des Schlüssels zurück
     *
     * @param   string
     * @return  string
     */
    public function getValue($key)
    {
        return $this->result[$key];
    }

    /**
     * Lädt die Benutzerdaten aus der Usertabelle anhand der ID des Benutzers
     *
     * @param   number
     * @return  array
     */
    public function getUserdataByID($id)
    {
        $this->db->setStatement('SELECT * FROM t:Benutzer WHERE Benutzer_Id = :id ');
        $this->db->bindParameter('id', 'i', $id);
        $this->result = $this->db->pselect1();
        if (count($this->result) > 0) {
            return $this->result;
        }
        else {
            return false;
        }
    }

}
