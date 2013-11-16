<?php

namespace DJP\View;

class Admin
{
    Public function render($content, $user, $response = false)
    {
        $view = new \lw_view(dirname(__FILE__) . '/Templates/Admin.phtml');
        
        $config = \DJP\Services\Registry::getInstance()->getEntry("config");
        
        #Setzen der Variablen, die im Template als Klassenvariablen zur
        #Verfügung stehen sollen.
        $view->response = $response;
        $view->content = $content;
		$view->user = $user;
		$view->roles = $config["roles"];
        $view->adminUrl = $config["url"]["client"]["admin"];
        $view->jQueryMin = $config["url"]["media"]. "js/jquery/jquery.1.9.1.min.js";
		$view->css = $config["url"]["media"]."css";
        
        return $view->render();
    }
}