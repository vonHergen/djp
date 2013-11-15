<?php

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
        $this->regEntries["config"] = $config = parse_ini_file(dirname(__FILE__)."/../Config/config.php", true);
        
        $db = new \lw_db_mysqli($config["lwdb"]["user"], $config["lwdb"]["pass"], $config["lwdb"]["host"], $config["lwdb"]["name"]);
        $db->connect();
        $this->regEntries["db"] = $db;

        $this->regEntries["request"] = new \lw_request();
        $this->regEntries["response"] = \DJP\Services\Response::getInstance();
        
        $user = new \lw_user($db);
        $auth = new \lw_auth();
        $auth->setUserObject($user);
        
        $this->regEntries["auth"] = $auth;
    }
    
    public function getEntry($key)
    {
        return $this->regEntries[$key];
    }
}