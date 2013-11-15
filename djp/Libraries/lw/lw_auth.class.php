<?php

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

  Ein user-object muß folgende Eigenschaften besitzen:
  1. function getUserdataByID($id);
  Diese Funktion übergibt ein userdata-Array (siehe 3) anhand einer
  eindeutigen ID

  2. function getUserdataByLogin($name, $pass);
  Diese Funktion übergibt ein userdata-Array (siehe 3) anhand einer
  eindeutigen Name/Passwort Kombination. Das Passwort wird unverschlüsselt
  übergeben. Es ist der jeweiligen user-Klasse überlassen, welche Art
  der Verschlüsselung gewählt wird.

  3. Das userdata Array muß wie folgt aufgebaut sein:
  array[key] = wert
  Die folgenden Schlüssel sind reserviert:
  id, name, password, groups, functions und godlevel.
  Weitere Inhalte können nach Belieben hinzugefügt werden
  [id] enthält die ID des Users
  [name] enthält den Loginnamen des Users
  [password] kann das Passwort des Users enthalten (wenn, dann nur verschlüsselt)
  [group] enthält eine Liste mit den GruppenID, welchen der User zugeordnet wurde (:1:34:56:2:)
  [functions]	enthält ein Array aus Funktionen, die der User ausführen darf.
  [godlevel] wird nur für Entwickler gesetzt, da hiermit automatisch alles erlaubt ist.

  Hinweis zu functions:
  dieses Array ist so aufgebaut, daß Funktionen zu funcional Units zusammengefasst werden.
  Jede functional Unit bekommt eine eindeutige Bezeichnung, ebenso wie die Funktionen, die
  dieser Unit zugeordnet werden. Das Array hat folgende Form:
  array[functionalUnit][function] = true/false

  Ein Beispielarray für einen Userdatasatz sieht so aus:

  $userdata['id']     = 15;
  $userdata['name']   = testuser;
  $userdata['pass']   = "Jh6dfTjhdbHbs";
  $userdata['groups'] = ":1:34:56:2:";
  $userdata['role_id']= 2;
  $userdata['functions']['testapplication1']['read']    = true;
  $userdata['functions']['testapplication1']['write']   = true;
  $userdata['functions']['testapplication1']['publish'] = true;
  $userdata['functions']['testapplication2']['read']    = true;

 */

/**
 * Die LW_Framework Authentifizierungsklasse
 *
 * @author      Dr. Andreas Eckhoff
 * @copyright   Copyright &copy; 2004/5 Logic Works GmbH
 * @package     LW Framework
 */
class lw_auth extends lw_object {

    /**
     * @var    array
     * @access private
     */
    private $userdata;

    /**
     * @var    boolean
     * @access private
     */
    private $loggedIn;

    /**
     * @var    object lw_userDAO
     * @access private
     */
    private $userDAO;

    /**
     * überprüft, ob es eine Session gibt. Wenn dies der Fall ist, werden deren Daten geladen.
     *
     * @param  string
     * @access public
     */
    function __construct($name = "default") {
        $this->sessionIdentifier = $name;
        $ok = $this->checkSession();
        if ($ok)
            $this->loadData();
    }

    /**
     * beendet eine Session. Sie wird auf Null gesetzt und explizit gelöscht
     *
     * @access public
     */
    public function destroySession() {
        if ($this->sessionIdentifier == "default") {
            session_unset();
            session_destroy();
        } else {
            unset($_SESSION[$this->sessionIdentifier]);
        }
    }

    /**
     * es wird ein Login ausgeführt. Gibt true zurück, wenn erfolgreich 
     * und false, wenn Passwort & Login nicht passen.
     *
     * @param  string
     * @param  string
     * @access public
     */
    public function login($name, $pass) {

        $values = $this->user->getUserdataByLogin($name, $pass);
        if (is_array($values)) {

            session_regenerate_id(true);
            session_unset();
            return $this->setLoadedData($values);
        } else {

            $this->userdata = false;
            $this->loggedIn = false;
            $this->destroySession();
            return false;
        }
    }

    /**
     * es wird ein logout durchgeführt.
     *
     * @access public
     */
    public function logout() {

        $this->userdata = false;
        $this->loggedIn = false;
        $this->destroySession();
    }

    /**
     * es wird geprüft ob der aktuelle Nutzer eingeloggt ist
     *
     * @access public
     */
    public function isLoggedIn() {
        if ($this->loggedIn == true) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * das übergeben Array wird in die User-Session geladen
     *
     * @param  array
     * @access private
     */
    private function putSession($userdata) {
        $_SESSION[$this->sessionIdentifier]['userdata'] = $userdata;
    }

    /**
     * die User-Sessiondaten werden zurückgegeben
     *
     * @access private
     */
    private function getSession() {
        return $_SESSION[$this->sessionIdentifier]['userdata'];
    }

    /**
     * es wird anhand der Sessiondaten geprüft ob der User angemeldet ist
     *
     * @access private
     */
    private function checkSession() {
        if (isset($_SESSION[$this->sessionIdentifier]) && is_array($_SESSION[$this->sessionIdentifier]['userdata']) && count($_SESSION[$this->sessionIdentifier]['userdata']) > 1) {
            if (lw_security::checkSession()) {
                $this->loggedIn = true;
                return true;
            } else {
                $this->userdata = false;
                $this->loggedIn = false;
                $this->destroySession();
                return false;
            }
        } else {
            $this->userdata = false;
            return false;
        }
    }

    /**
     * es werden die Userdaten aus der Session ausgelesen
     *
     * @access public
     */
    public function loadData() {
        if ($this->loggedIn == true) {
            if (!is_array($this->userdata) || count($this->userdata) < 1) {
                $this->userdata = $this->getSession();
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * es können Userdaten einzeln abgefragt werden
     *
     * @param  string
     * @access public
     */
    public function getUserdata($key) {
        return $this->userdata[$key];
    }

    /**
     * die User-Sessiondaten werden neu aus der DB geladen und gesetzt
     *
     * @access public
     */
    public function reloadData() {
        #if ($this->isGodmode()) {
        #    return true;
        #}

        $values = $this->user->getUserdataByID($this->userdata['Benutzer_Id']);
        if (count($values) > 0) {
            return $this->setLoadedData($values);
        } else {
            $this->userdata = false;
            $this->loggedIn = false;
            $this->destroySession();
            return false;
        }
    }

    /**
     * die geladenen Daten werden in die Session und die Klassenvariablen geladen
     *
     * @param  array
     * @access private
     */
    private function setloadedData($erg) {
        $this->userdata = $erg;
        $this->putSession($erg);
        $this->loggedIn = true;
        lw_security::setSessionSecurity();
        return true;
    }

    /**
     * das userDAO-Object wird gesetzt
     *
     * @param  object
     * @access private
     */
    public function setUserObject($user) {
        $this->user = $user;
    }

    /**
     * hierüber können zusätzliche Werte über einen Schlüssel in der Session gespeichert werden
     *
     * @param  string
     * @param  mixed
     * @access public
     */
    public function setAdditionalVars($key, $value) {
        $_SESSION[$this->sessionIdentifier]['additional'][$key] = $value;
    }

    /**
     * hierüber können zusätzliche Werte über einen Schlüssel aus der Session abgerufen werden
     *
     * @param  string
     * @access public
     */
    public function getAdditionalVars($key) {
        return $_SESSION[$this->sessionIdentifier]['additional'][$key];
    }

    /**
     * hierüber können zusätzliche Werte über einen Schlüssel aus der Session gelöscht werden
     *
     * @param  string
     * @access public
     */
    public function unsetAdditionalVars($key) {
        $_SESSION[$this->sessionIdentifier]['additional'][$key] = false;
    }

    /**
     * hiermit wird gecheckt, ob ein User eine bestimmte Funktion ausführen darf
     *
     * @param  string
     * @param  string
     * @return bool
     * @access public
     */
    public function isAllowed($function, $level = false) {
        if ($function == "godmode") {
            return $this->isGodmode();
        }
        if ($this->userdata['admintype'] == "main" || $this->userdata['admintype'] == "godmode") {
            return true;
        }
        if (strstr($this->userdata['functions'], ":" . $function . ":") || strstr($this->userdata['functions'], $function . "_" . $level)) {
            return true;
        }
        return false;
    }

    /**
     * hiermit wird gecheckt, ob ein User einer bestimmten Gruppe zugeordnet ist
     *
     * @param  number
     * @return bool
     * @access public
     */
    public function isGodmode() {
        if ($this->userdata['admintype'] == "godmode") {
            return true;
        }
        return false;
    }

}
