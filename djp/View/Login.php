<?php

namespace DJP\View;

class Login
{
    Public function render($error = false)
    {
        $view = new \lw_view(dirname(__FILE__) . '/Templates/Login.phtml');
        
        $config = \DJP\Services\Registry::getInstance()->getEntry("config");
        $view->actionUrl = $config["url"]["client"]["admin"];
        $view->error = $error;
        
        return $view->render();
    }
}