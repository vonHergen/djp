<?php
/**
 * Über die Registry können wichtige Hilfsklassen geladen werden.
 */
namespace DJP\Services;

class Registry
{
    private static $instance = null;
    private $regEntries;
    
    
    public static function getInstance()
    {
        if(self::$instance == null)
        {
            self::$instance = new \DJP\Services\Registry();
        }
        return self::$instance;
    }
    
    public function __construct()
    {
        $this->init();
    }
    
    private function init()
    {
        # Systemkonfiguration laden
        $this->regEntries["config"] = $config = parse_ini_file(dirname(__FILE__)."/../Config/config.php", true);
        
        # MySQL Verbindungsobjekt laden und die Verbindung aufbauen.
        $db = new \lw_db_mysqli($config["lwdb"]["user"], $config["lwdb"]["pass"], $config["lwdb"]["host"], $config["lwdb"]["name"]);
        $db->connect();
        $this->regEntries["db"] = $db;

        # Request Objekt laden ( Inputfilter für POST und GET Parameter)
        $this->regEntries["request"] = new \lw_request();
        
        # Response Objekt laden ( Universall Objekt ablegen von Daten )
        $this->regEntries["response"] = \DJP\Services\Response::getInstance();
        
        # Authentifizierungsobjekt laden und mit den Benutzerinformationen füllen
        $user = new \lw_user($db);
        $auth = new \lw_auth();
        $auth->setUserObject($user);
        
        $this->regEntries["auth"] = $auth;
    }
    
    /**
     * Objekt anhand des Keys laden.
     * 
     * @param string $key
     * @return object
     */
    public function getEntry($key)
    {
        return $this->regEntries[$key];
    }
}