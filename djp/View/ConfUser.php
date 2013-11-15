<?php

namespace DJP\View;

class ConfUser
{
    Public function render($userList)
    {
        $view = new \lw_view(dirname(__FILE__) . '/Templates/ConfUser.phtml');                
        
        $config = \DJP\Services\Registry::getInstance()->getEntry("config");
        
        $view->userList = $userList;
        $view->adminUrl = $config["url"]["client"]["admin"];
        
        return $view->render();
    }
}