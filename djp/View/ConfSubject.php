<?php

namespace DJP\View;

class ConfSubject
{
    Public function render($subjectList)
    {
        $view = new \lw_view(dirname(__FILE__) . '/Templates/ConfSubject.phtml');                
        
        $config = \DJP\Services\Registry::getInstance()->getEntry("config");
        
        $view->subjectList = $subjectList;
        $view->adminUrl = $config["url"]["client"]["admin"];
        
        return $view->render();
    }
}
