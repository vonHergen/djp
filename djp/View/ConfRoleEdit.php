<?php

namespace DJP\View;

class ConfRoleEdit
{
    Public function render($role = false, $addOrEdit = false, $errors = false)
    {
        $view = new \lw_view(dirname(__FILE__) . '/Templates/ConfRoleAddEdit.phtml');                
        
        $config = \DJP\Services\Registry::getInstance()->getEntry("config");
        
        $view->role = $role;
        $view->adminUrl = $config["url"]["client"]["admin"];
        $view->errors = $errors;
        if(!$addOrEdit){
            $view->actionUrl = $config["url"]["client"]["admin"]."?cmd=confrole&task=add";
            $view->cmd = "Anlegen";
        }else{
            $view->actionUrl = $config["url"]["client"]["admin"]."?cmd=confrole&task=edit&id=".$role["Typ_Id"];
            $view->cmd = "Bearbeiten";
        }
        
        return $view->render();
    }
}
