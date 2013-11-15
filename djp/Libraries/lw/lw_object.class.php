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
 * Die "LW_OBJECT" Klasse ist die grundlegende Klasse für die meisten Framework Klassen
 * 
 * @package  Framework
 * @author   Andreas Eckhoff
 * @since    PHP 5.0
 */
class lw_object
{

    /**
     * schaltet den Debugging Mode an oder aus
     * @var bool
     */
    private $debug;

    /**
     * beinhaltet den Namen der Klasse
     * @var string
     */
    private $classname;

    /**
     * beinhaltet die Kommandos mit den entsprechend aufzurufenden Funktionen
     * @var array
     */
    protected $commands = array();

    /**
     * beinhaltet das aktuelle Kommando
     * @var object
     */
    protected $cmd;

    /**
     * Constructor
     * hier werden die Grundvariablen gesetzt und der Klasse zur Verf�gung gestellt.
     *
     */
    public function __construct()
    {
        $this->debug = false;
        $this->classname = get_class($this);
    }

    /**
     * getDescription
     * es wird eine Beschreibung der KLasse zur�ckgegeben
     *
     */
    public function getDescription()
    {
        return "lwobject: Basisobjekt des LW-Systems !";
    }

    /**
     * toString
     * wandelt die KLasse in einen String um.
     *
     * @return  string
     */
    public function toString()
    {
        return $this->getDescription();
    }

    /**
     * loadFile
     * lädt den kompletten Inhalt eines Files und gibt diesen zurück
     *
     * @param   string
     * @return  string
     */
    protected function loadFile($file)
    {
        if (!file_exists($file)) {
            throw new Exception("[lw_object::loadFile] Das File (" . $file . ") existiert nicht !");
        }
        $fileopen = fopen($file, "r");
        if (!$fileopen) {
            throw new Exception("[lw_object::loadFile] Das File (" . $file . ") konnte nciht geöffnet werden !");
        }
        $file_data = fread($fileopen, filesize($file));
        fclose($fileopen);
        return $file_data;
    }

    /**
     * writeFile
     * speichert einen text in einem File
     *
     * @param   string
     * @param   string
     * @return  bool
     */
    protected function writeFile($file, $data)
    {
        $fileopen = fopen($file, "w+");
        if (!$fileopen) {
            throw new Exception("[lw_object::writeFile] Das File konnte nicht geöffnet werden !<!-- " . $file . " -->");
        }
        $ok = fwrite($fileopen, $data);
        fclose($fileopen);
        return $ok;
    }

    /**
     * appendFile
     * speichert einen text in einem File
     *
     * @param   string
     * @param   string
     * @return  bool
     */
    public function appendFile($file, $data)
    {
        $fileopen = @fopen($file, "a+");
        if (!$fileopen) {
            throw new Exception("[lw_object::appendFile] Das File konnte nciht geöffnet werden !");
        }
        $ok = fwrite($fileopen, $data);
        fclose($fileopen);
        return $ok;
    }

    /**
     * jokercmp
     * überprüft einen String auf das Vorkommen eines anderen, wobei 
     * mit Joker gearbeitet werden kann. Siehe Beispiele:
     * if(jokercmp('myfile.txt','*.txt')) [...]       // this is true
     * if(jokercmp('file001.jpg','file??.jpg')) [...] // this is false
     *
     * @param   string
     * @param   string
     * @return  bool
     */
    protected function jokercmp($string, $mask)
    {
        static $in = array('.', '^', '$', '{', '}', '(', ')', '[', ']', '+', '*', '?');
        static $out = array('\\.', '\\^', '\\$', '\\{', '\\}', '\\(', '\\)', '\\[', '\\]', '\\+', '.*', '.');

        $mask = '^' . str_replace($in, $out, $mask) . '$';

        return(ereg($mask, $string));
    }

    /**
     * URL bauen
     *
     * Diese Funktion baut eine komplette URL zusammen aus einer Basis (default = aufrufendes Script), den aktuellen
     * Variablen die per GET-Methode übergeben wurden und optionalen weiteren Varibalen, die die aktuellen GET-Variablen
     * ergänzen und/oder überschreiben.
     *
     * @param    array	$new_args   Array ($variable => $wert) mit den Argumenten, die der aktuellen URL hinzuzufügen oder in ihr zu ersetzen sind
     * @param    array	$unset		Array mit den Varibalen die aus der URL entfernt werden sollen
     * @param	string  $base		URI des Scriptes auf desen Basis die URL gebaut werden soll, wenn nicht übergeben wird $PHP_SELF verwendet.
     *
     * @return   string
     */
    public function buildUrl($new_args = FALSE, $unset = FALSE, $base = FALSE)
    {
        $registry = lw_registry::getInstance();
        $obj = $registry->getEntry('fGet');
        $args = $obj->_source;

        if ($new_args) {
            foreach ($new_args as $key => $value) {
                $args[$key] = $value;
            }
        }

        if ($unset) {
            if (is_array($unset)) {
                foreach ($unset as $value) {
                    unset($args[$value]);
                }
            }
            else {
                unset($args[$unset]);
            }
        }

        if ($base) {
            $url = $base;
        }
        else {
            $url = $_SERVER['PHP_SELF'];
        }

        $count = 0;
        foreach ($args as $key => $value) {
            if ($count < 1) {
                $url .= "?";
            }
            else {
                $url .= "&";
            }
            if (!is_array($value)) {
                $url .= $key . "=" . rawurlencode($value);
            }
            $count++;
        }
        return $url;
    }

    /**
     * simpleURL
     * anhand des übergebenen Arrays wird eine URL zusammengebaut, die sich auf index.php bezieht
     *
     * @param    array	$new_args   Array ($variable => $wert) mit den Argumenten, die der aktuellen URL hinzuzufügen oder in ihr zu ersetzen sind
     *
     * @return   string
     */
    protected function simpleURL($args)
    {
        if (empty($args)) {
            return "index.php";
        }

        foreach ($args as $key => $value) {
            $argline.=$key . "=" . $value . "&";
        }

        $argline = substr($argline, 0, strlen($argline) - 1);

        return "index.php?" . $argline;
    }

    /**
     * deprecated !!!!
     */
    function page_reload($url)
    {
        $this->pageReload($url);
    }

    /**
     * urlWithAlert
     * Es wird ein JavaScript Confirm mit dem übergebenen text aufgerufen und bei Zustimmung
     * auf die übergebene URL weitergeleitet.
     *
     * @param    string 	text
     * @param    string 	url
     *
     * @return   string
     */
    function urlWithAlert($text, $url)
    {
        return "javascript:if(confirm('" . $text . "')){self.location.replace('" . $url . "');}";
    }

    /**
     * pageReload
     * nur noch ein wrapper für die neue forceRedirect Funktion
     *
     * @param    array	url
     *
     * @return   string
     */
    public function pageReload($url)
    {
        $url = str_replace("&amp;", "&", $url);
        //$this->forceReload($url);
        echo '<html>' . PHP_EOL;
        echo '    <head><meta http-equiv="Refresh" content="0;url=' . $url . '" /></head>' . PHP_EOL;
        echo '    <body onload="try {self.location.href=' . "'" . $url . "'" . ' } catch(e) {}"><a href="' . $url . '">Redirect </a></body>' . PHP_EOL;
        echo '</html>' . PHP_EOL;
        exit();
    }

    /**
     * forceReload
     * Es wird auf die angebene URl weitergeleitet, via diverser Methoden)
     *
     * @param    array	url
     *
     * @return   string
     */
    function forceReload($url, $die=true)
    {
        die($url);
        if (!headers_sent()) {
            ob_end_clean();
            //header("Location: " . $url);
            //header("HTTP/1.1 301 Moved Permanently");
            header("Location: " . $url);
            header("Connection: close");
        }
        printf("<HTML>");
        printf("<META http-equiv='Refresh' content='0;url=%s'>", $url);
        printf("<BODY onload='try {self.location.href='%s' } catch(e) {}'><a href='%s'>Redirect </a></BODY>", $url, $url);
        printf("</HTML>");
        if ($die)
            die();
    }

    /**
     * handleSessionVar
     * Es wird ein Wert einer Variable gelesen. Ist diese Variable in einem GET/POST enthalten sow rid dieser Wrte genommen,
     * ist der Wert in der SESSION enthalten, so wird dieser genommen, ansonsten wird er auf den übergenen default gesetzt.
     * POST wird nur dann genommen, wenn der post-Parameter auf true gesetzt wurde, ansonsten wird GET verwendet.
     *
     * @param    string	name   
     * @param    string	default
     * @param    bool	post
     *
     * @return   mixed
     */
    public function handleSessionVar($name, $default, $post=false)
    {
        $registry = lw_registry::getInstance();
        $db = false;
        if ($db)
            echo "verlangt: " . $name . "<br>\n";
        $inputFilter = $registry->getEntry('request');
        $value = $inputFilter->getRaw($name);
        if ($value) {
            if ($db)
                echo "gegeben(post/get): " . $value . "<br>\n";
            $_SESSION[$name] = $value;
            return $value;
        }
        elseif (isset($_SESSION[$name])) {
            if ($db)
                echo "gegeben(SESSION): " . $_SESSION[$name] . "<br>\n";
            return $_SESSION[$name];
        }
        else {
            if ($db)
                echo "gegeben(default): " . $default . "<br>\n";
            $_SESSION[$name] = $default;
            return $default;
        }
    }

    /**
     * setSessionVar
     * es wird eine SessionVariable gesetzt
     *
     * @param    string	name   
     * @param    string	value
     */
    protected function setSessionVar($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    /**
     * getSessionVar
     * es wird eine SessionVariable geladen
     *
     * @param    string	name   
     *
     * @return   string	value
     */
    protected function getSessionVar($name)
    {
        return $_SESSION[$name];
    }

    /**
     * printArray
     * debug Funktion zur formatierten Ausgabe eines Arrays
     *
     * @param    array arr
     */
    protected function pA($arr)
    {
        echo "<p><pre>\n";
        print_r($arr);
        echo "</pre></p>\n";
    }

    /**
     * formateDate
     * es wird ein Date ein 8/12/14 stelliger Datum/Zeit_wert entegegengenommen und in ein
     * formatiertes Datum umgewandelt:
     * 8  stellig: d.m.y
     * 12 stellig: d.m.y h:m
     * 14 stellig: d.m.y h:m:s
     *
     * @param    string date
     */
    public function formatDate($date)
    {
        if (strlen($date) == 8) {
            $year = substr($date, 0, 4);
            $month = substr($date, 4, 2);
            $day = substr($date, 6, 2);
            return $day . "." . $month . "." . $year;
        }
        elseif (strlen($date) == 12) {
            $year = substr($date, 0, 4);
            $month = substr($date, 4, 2);
            $day = substr($date, 6, 2);
            $hour = substr($date, 8, 2);
            $minute = substr($date, 10, 2);
            return $day . "." . $month . "." . $year . " " . $hour . ":" . $minute;
        }
        elseif (strlen($date) == 14) {
            $year = substr($date, 0, 4);
            $month = substr($date, 4, 2);
            $day = substr($date, 6, 2);
            $hour = substr($date, 8, 2);
            $minute = substr($date, 10, 2);
            $sec = substr($date, 12, 2);
            return $day . "." . $month . "." . $year . " " . $hour . ":" . $minute . ":" . $sec;
        }
        else {
            return false;
        }
    }

    /**
     * Convert SimpleXMLElement object to array
     * Copyright Daniel FAIVRE 2005 - www.geomaticien.com
     * Copyleft GPL license
     */
    function simplexml2array($xml)
    {
        if (get_class($xml) == 'SimpleXMLElement') {
            $attributes = $xml->attributes();
            foreach ($attributes as $k => $v) {
                if ($v)
                    $a[$k] = (string) $v;
            }
            $x = $xml;
            $xml = get_object_vars($xml);
        }
        if (is_array($xml)) {
            if (count($xml) == 0)
                return (string) $x; // for CDATA
            foreach ($xml as $key => $value) {
                $r[$key] = simplexml2array($value);
            }
            if (isset($a))
                $r['@'] = $a;    // Attributes
            return $r;
        }
        return (string) $xml;
    }

    function getRealIP()
    {
        if (getenv('HTTP_CLIENT_IP')) {
            $IP = getenv('HTTP_CLIENT_IP');
        }
        elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $IP = getenv('HTTP_X_FORWARDED_FOR');
        }
        elseif (getenv('HTTP_X_FORWARDED')) {
            $IP = getenv('HTTP_X_FORWARDED');
        }
        elseif (getenv('HTTP_FORWARDED_FOR')) {
            $IP = getenv('HTTP_FORWARDED_FOR');
        }
        elseif (getenv('HTTP_FORWARDED')) {
            $IP = getenv('HTTP_FORWARDED');
        }
        else {
            $IP = $_SERVER['REMOTE_ADDR'];
        }
        return $IP;
    }

    function getRandomString($size=6)
    {
        $string = "";
        $chars = str_shuffle("23456789QWERTZUPLKJHGFDSAYXCVBNMqwertzpkujhgfdsayxcvbnm");
        for ($i = 0; $i < $size; $i++) {
            $string.=substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $string;
    }

    // --------------------- SANITIZING FUNCTIONS ---------------------

    //
	//
	
    /**
     * Washes strings from unwanted noise.
     * taken from 
     * CakePHP :  Rapid Development Framework <http://www.cakephp.org/>
     * Copyright (c)	2006, Cake Software Foundation, Inc.
     * 								1785 E. Sahara Avenue, Suite 490-204
     * 								Las Vegas, Nevada 89104
     *
     * Licensed under The MIT License (http://www.opensource.org/licenses/mit-license.php)
     * Redistributions of files must retain the above copyright notice.
     *
     * original code was modified by LogicWorks GmbH
     */
    function complete($string, $allowed = array())
    {
        $allow = null;
        if (!empty($allowed)) {
            foreach ($allowed as $value) {
                $allow .= "\\$value";
            }
        }

        if (is_array($string)) {
            foreach ($string as $key => $value) {
                $cleaned[$key] = preg_replace("/[^{$allow}a-zA-Z0-9]/", "", $value);
            }
        }
        else {
            $cleaned = preg_replace("/[^{$allow}a-zA-Z0-9]/", "", $string);
        }
        return $cleaned;
    }

    function sql($string)
    {
        if (!ini_get('magic_quotes_gpc')) {
            $string = addslashes($string);
        }
        return $string;
    }

    function removeMagicQuotes($string)
    {
        if (ini_get('magic_quotes_gpc')) {
            $string = stripslashes($string);
        }
        return $string;
    }

    function html($string, $remove = false)
    {
        if ($remove) {
            $string = strip_tags($string);
        }
        else {
            $patterns = array("/\&/", "/</", "/>/", '/"/', "/'/", "/\(/", "/\)/", "/\+/", "/-/");
            $replacements = array("&amp;", "&lt;", "&gt;", "&quot;", "&#39;", "&#40;", "&#41;", "&#43;", "&#45;");
            $string = preg_replace($patterns, $replacements, $string);
        }
        return $string;
    }

    function cleanArray(&$toClean)
    {
        return $this->cleanArrayR($toClean);
    }

    function cleanArrayR(&$toClean)
    {
        if (is_array($toClean)) {
            while (list($k, $v) = each($toClean)) {
                if (is_array($toClean[$k])) {
                    $this->cleanArray($toClean[$k]);
                }
                else {
                    $toClean[$k] = $this->cleanValue($v);
                }
            }
        }
        else {
            return null;
        }
    }

    function cleanValue($val)
    {
        if ($val == "") {
            return "";
        }
        //Replace odd spaces with safe ones
        $val = str_replace(" ", " ", $val);
        $val = str_replace(chr(0xCA), "", $val);
        //Encode any HTML to entities (including \n --> <br />)
        $val = $this->html($val);
        //Double-check special chars and remove carriage returns
        //For increased SQL security
        $val = preg_replace("/\\\$/", "$", $val);
        $val = preg_replace("/\r/", "", $val);
        $val = str_replace("!", "!", $val);
        $val = str_replace("'", "'", $val);
        //Allow unicode (?)
        $val = preg_replace("/&amp;#([0-9]+);/s", "&#\\1;", $val);
        //Add slashes for SQL
        $val = $this->sql($val);
        //Swap user-inputted backslashes (?)
        $val = preg_replace("/\\\(?!&amp;#|\?#)/", "\\", $val);
        return $val;
    }

    //additional functions
    function emptyGlobals()
    {
        $superglobals = array(
            &$_POST, &$_GET, &$_COOKIE,
            &$_ENV, &$_SERVER, &$_REQUEST,
            &$HTTP_POST_VARS, &$HTTP_GET_VARS,
            &$HTTP_COOKIE_VARS, &$HTTP_ENV_VARS,
            &$HTTP_SERVER_VARS);

        foreach ($superglobals as $global) {
            if (is_array($global)) {
                foreach ($global as $key => $value) {
                    unset($global[$key]);
                }
            }
        }

        unset($GLOBALS['HTTP_GET_VARS']);
        unset($GLOBALS['HTTP_POST_VARS']);
        unset($GLOBALS['HTTP_POST_FILES']);

        unset($GLOBALS['_POST']);
        unset($GLOBALS['_GET']);
        unset($GLOBALS['_REQUEST']);
        unset($GLOBALS['_FILES']);
    }

    function lwPrepareString($string, $flag=true)
    {
        $string = trim($string);
        if ($flag === true)
            $string = strip_tags($string);
        return $string;
    }

    function lwStringClean($val, $flag=false)
    {
        if ($flag == true) {
            $val = stripslashes($val);
        }
        if ($val == "") {
            return "";
        }
        //Replace odd spaces with safe ones
        $val = str_replace(" ", " ", $val);
        $val = str_replace(chr(0xCA), "", $val);
        //Encode any HTML to entities
        $val = $this->html($val);
        //Double-check special chars and remove carriage returns
        //For increased SQL security
        $val = preg_replace("/\\\$/", "$", $val);
        $val = preg_replace("/\r/", "", $val);
        $val = str_replace("!", "!", $val);
        $val = str_replace("'", "'", $val);
        //Allow unicode (?)
        $val = preg_replace("/&amp;#([0-9]+);/s", "&#\\1;", $val);
        //Swap user-inputted backslashes (?)
        $val = preg_replace("/\\\(?!&amp;#|\?#)/", "\\", $val);
        return $val;
    }

    function escapeArrayValues($array)
    {
        if (!is_array($array))
            return $array;
        $escapedArray = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $decodedValue = lw_object::escapeArrayValues($value);
            }
            else {
                //$decodedValue = addslashes($value);
                $value = str_replace("\\", addslashes("\\"), $value);
                $decodedValue = str_replace("'", "\\'", $value);
            }
            $decodedKey = addslashes($key);
            $escapedArray[$decodedKey] = $decodedValue;
        }
        return $escapedArray;
    }

    function utf8_compliant($input)
    {
        if ($input != @iconv("UTF-8", "UTF-8", $input))
            return false;
        return true;
    }

    function remove_invalid_utf8($t)
    {
        return iconv("UTF-8", "UTF-8//IGNORE", $t);
    }

    function utf8_strlen($string)
    {
        return strlen(utf8_decode($str));
    }

    //
    //
	// --------------------- SANITIZING FUNCTIONS ---------------------
    // --------------------- GETTER / SETTER ---------------------
    //
	//	

    function setObject($name, $object)
    {
        if (is_object($object)) {
            $this->objects[$name] = $object;
        }
    }

    function getObject($name)
    {
        if (is_object($this->objects[$name])) {
            return $this->objects[$name];
        }
        elseif (is_object(lw_registry::getInstance()->getEntry($name))) {
            $this->setObject($name, lw_registry::getInstance()->getEntry($name));
            return lw_registry::getInstance()->getEntry($name);
        }
    }

    //
    //
	// --------------------- GETTER / SETTER ---------------------
}
?>
