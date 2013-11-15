<?php

namespace DJP\View;

class ConfEducationEdit
{
    Public function render($education = false, $addOrEdit = false, $errors = false)
    {
        $view = new \lw_view(dirname(__FILE__) . '/Templates/ConfEducationAddEdit.phtml');                
        
        $config = \DJP\Services\Registry::getInstance()->getEntry("config");
        
        $view->education = $education;
        $view->adminUrl = $config["url"]["client"]["admin"];
        $view->errors = $errors;
        if(!$addOrEdit){
            $view->actionUrl = $config["url"]["client"]["admin"]."?cmd=confeducation&task=add";
            $view->cmd = "Anlegen";
        }else{
            $view->actionUrl = $config["url"]["client"]["admin"]."?cmd=confeducation&task=edit&id=".$education["Bildungsgang_Id"];
            $view->cmd = "Bearbeiten";
        }
        
        return $view->render();
    }
}
