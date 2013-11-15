<?php

namespace DJP\View;

class ConfSubjectEdit
{
    Public function render($subject = false, $addOrEdit = false, $errors = false)
    {
        $view = new \lw_view(dirname(__FILE__) . '/Templates/ConfSubjectAddEdit.phtml');                
        
        $config = \DJP\Services\Registry::getInstance()->getEntry("config");
        
        $view->subject = $subject;
        $view->adminUrl = $config["url"]["client"]["admin"];
        $view->errors = $errors;
        if(!$addOrEdit){
            $view->actionUrl = $config["url"]["client"]["admin"]."?cmd=consubject&task=add";
            $view->cmd = "Anlegen";
        }else{
            $view->actionUrl = $config["url"]["client"]["admin"]."?cmd=confsubject&task=edit&id=".$subject["Fach_Id"];
            $view->cmd = "Bearbeiten";
        }
        
        return $view->render();
    }
}
