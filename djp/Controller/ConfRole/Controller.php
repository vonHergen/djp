<?php

namespace DJP\Controller\ConfRole;

class Controller
{
    public function execute()
    {
        $auth = \DJP\Services\Registry::getInstance()->getEntry("auth");
        $response = \DJP\Services\Registry::getInstance()->getEntry("response");
        $request = \DJP\Services\Registry::getInstance()->getEntry("request");
        $config = \DJP\Services\Registry::getInstance()->getEntry("config");
        $queryHandler = new \DJP\Model\Backend\ConfRole\DataHandler\QueryHandler();
        $commandHandler = new \DJP\Model\Backend\ConfRole\DataHandler\CommandHandler();
        
        /**
         * /F200/
         * 
         * Bestehenden Bildungsgang bearbeiten.
         */
        if($request->getAlnum("task") == "edit") {
            if($request->getInt("sent")) {
                $array = $request->getPostArray();
                unset($array["sent"]);
                
                $isValid = new \DJP\Model\Backend\ConfRole\Specification\isValid();
                $isValid->setValues($array);
				
				if (!$isValid->validate()) {
                    $view = new \DJP\View\ConfRoleEdit();
                    $content = $view->render($queryHandler->getRoleById($request->getInt("id")), true, $isValid->getErrors());
                }
                else {
                    $commandHandler->updateRoleById($request->getInt("id"), $array);
                    $auth->reloadData();
                    \DJP\Services\Page::reload($config["url"]["client"]["admin"] . "?cmd=confrole&response=4");
                }

            } else {
                $view = new \DJP\View\ConfRoleEdit();
                $content = $view->render($queryHandler->getRoleById($request->getInt("id")), true, false);
			}
        /**
         * Neuen Bildungsgang anlegen.
         */
        } elseif ($request->getAlnum("task") == "add") {
			if($request->getInt("sent")) {
				$array = $request->getPostArray();
				unset($array["sent"]);
				
				$isValid = new \DJP\Model\Backend\ConfRole\Specification\isValid();
				$isValid->setValues($array);
				
				if (!$isValid->validate()) {
					$array2 = array();
                    $view = new \DJP\View\ConfRoleEdit();
                    foreach ($array as $key => $value) {
                        $array2[ucfirst($key)] = $value;
                    }
                   $content = $view->render($array2, false, $isValid->getErrors());
                }
                else {
                    $commandHandler->addRole($array);
                    \DJP\Services\Page::reload($config["url"]["client"]["admin"] . "?cmd=confrole&response=5");
                }
			} else {
				$view = new \DJP\View\ConfRoleEdit();
				$content = $view->render();
			}
        /**
         * /F200/
         * 
         * Bestehenden Bildungsgang entfernen.
         */
		} elseif ($request->getAlnum("task") == "delete") {
			$commandHandler->deleteRoleById($request->getInt("id"));
            \DJP\Services\Page::reload($config["url"]["client"]["admin"] . "?cmd=confrole&response=6");
		} else {
            $view = new \DJP\View\ConfRole();
            $roleList = $queryHandler->getRoleList();
          
            $content = $view->render($roleList);
        }
        return $content;
    }

}

