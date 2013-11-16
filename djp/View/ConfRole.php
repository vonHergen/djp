<?php

namespace DJP\View;

class ConfRole
{
    Public function render($roleList)
    {
        $view = new \lw_view(dirname(__FILE__) . '/Templates/ConfRole.phtml');                
        
        $config = \DJP\Services\Registry::getInstance()->getEntry("config");
        
        $view->roleList = $roleList;
        $view->adminUrl = $config["url"]["client"]["admin"];
        
        return $view->render();
    }
}
