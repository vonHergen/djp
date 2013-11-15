<?php

namespace DJP\View;

class ConfEducation
{
    Public function render($educationList)
    {
        $view = new \lw_view(dirname(__FILE__) . '/Templates/ConfEducation.phtml');                
        
        $config = \DJP\Services\Registry::getInstance()->getEntry("config");
        
        $view->educationList = $educationList;
        $view->adminUrl = $config["url"]["client"]["admin"];
        
        return $view->render();
    }
}
