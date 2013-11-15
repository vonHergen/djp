<?php

namespace DJP\View;

class ConfLfield
{
    Public function render($lfieldList)
    {
        $view = new \lw_view(dirname(__FILE__) . '/Templates/ConfLfield.phtml');                
        
        $config = \DJP\Services\Registry::getInstance()->getEntry("config");
        
        $view->lfieldList = $lfieldList;
        $view->adminUrl = $config["url"]["client"]["admin"];
        
        return $view->render();
    }
}
