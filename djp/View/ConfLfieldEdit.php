<?php

namespace DJP\View;

class ConfLfieldEdit
{
    Public function render($lfield = false, $addOrEdit = false, $errors = false)
    {
        $view = new \lw_view(dirname(__FILE__) . '/Templates/ConfLfieldAddEdit.phtml');                
        
        $config = \DJP\Services\Registry::getInstance()->getEntry("config");
        
        $view->lfield = $lfield;
        $view->adminUrl = $config["url"]["client"]["admin"];
        $view->errors = $errors;
        if(!$addOrEdit){
            $view->actionUrl = $config["url"]["client"]["admin"]."?cmd=conflfield&task=add";
            $view->cmd = "Anlegen";
        }else{
            $view->actionUrl = $config["url"]["client"]["admin"]."?cmd=conflfield&task=edit&id=".$lfield["Lernfeld_Id"];
            $view->cmd = "Bearbeiten";
        }
        
        return $view->render();
    }
}
