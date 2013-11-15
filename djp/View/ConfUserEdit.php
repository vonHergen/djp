<?php

namespace DJP\View;

class ConfUserEdit
{
    Public function render($user = false, $roles = false, $educationList = false, $addOrEdit = false, $errors = false)
    {
        $view = new \lw_view(dirname(__FILE__) . '/Templates/ConfUserAddEdit.phtml');                
        
        $config = \DJP\Services\Registry::getInstance()->getEntry("config");
        
        $view->user = $user;
        $view->roles = $roles;
        $view->educationList = $educationList;
        $view->adminUrl = $config["url"]["client"]["admin"];
        $view->errors = $errors;
        if(!$addOrEdit){
            $view->actionUrl = $config["url"]["client"]["admin"]."?cmd=confuser&task=add";
            $view->cmd = "Anlegen";
        }else{
            $view->actionUrl = $config["url"]["client"]["admin"]."?cmd=confuser&task=edit&id=".$user["Benutzer_Id"];
            $view->cmd = "Bearbeiten";
        }
        
        return $view->render();
    }
}