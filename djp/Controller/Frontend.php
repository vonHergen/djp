<?php
namespace DJP\Controller;

class Frontend
{

    public function __construct()
    {
    }

    public function buildPageOutput()
    {
        $config = \DJP\Services\Registry::getInstance()->getEntry("config");
        
        print_r($config);die();
    }

}
