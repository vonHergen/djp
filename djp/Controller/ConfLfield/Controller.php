<?php

namespace DJP\Controller\ConfLfield;

class Controller
{
    public function execute()
    {
        $auth = \DJP\Services\Registry::getInstance()->getEntry("auth");
        $response = \DJP\Services\Registry::getInstance()->getEntry("response");
        $request = \DJP\Services\Registry::getInstance()->getEntry("request");
        $config = \DJP\Services\Registry::getInstance()->getEntry("config");
        $queryHandler = new \DJP\Model\Backend\ConfLfield\DataHandler\QueryHandler();
        $commandHandler = new \DJP\Model\Backend\ConfLfield\DataHandler\CommandHandler();
        
        /**
         * /F130/
         * 
         * Bestehendes Lernfeld bearbeiten.
         */
        if($request->getAlnum("task") == "edit") {
            if($request->getInt("sent")) {
                $array = $request->getPostArray();
                unset($array["sent"]);
                
                $isValid = new \DJP\Model\Backend\ConfLfield\Specification\isValid();
                $isValid->setValues($array);
				
				if (!$isValid->validate()) {
                    $view = new \DJP\View\ConfLfieldEdit();
                    $content = $view->render($queryHandler->getLfieldById($request->getInt("id")), true, $isValid->getErrors());
                }
                else {
                    $commandHandler->updateLfieldById($request->getInt("id"), $array);
                    $auth->reloadData();
                    \DJP\Services\Page::reload($config["url"]["client"]["admin"] . "?cmd=conflfield&response=4");
                }

            } else {
                $view = new \DJP\View\ConfLfieldEdit();
                $content = $view->render($queryHandler->getLfieldById($request->getInt("id")), true, false);
			}
        /**
         * /F80/
         * 
         * Neues Lernfeld anlegen.
         */
        } elseif ($request->getAlnum("task") == "add") {
			if($request->getInt("sent")) {
				$array = $request->getPostArray();
				unset($array["sent"]);
				
				$isValid = new \DJP\Model\Backend\ConfLfield\Specification\isValid();
				$isValid->setValues($array);
				
				if (!$isValid->validate()) {
					$array2 = array();
                    $view = new \DJP\View\ConfLfieldEdit();
                    foreach ($array as $key => $value) {
                        $array2[ucfirst($key)] = $value;
                    }
                   $content = $view->render($array2, false, $isValid->getErrors());
                }
                else {
                    $commandHandler->addLfield($array);
                    \DJP\Services\Page::reload($config["url"]["client"]["admin"] . "?cmd=conflfield&response=5");
                }
			} else {
				$view = new \DJP\View\ConfLfieldEdit();
				$content = $view->render();
			}
        /**
         * /F130/
         * 
         * Bestehendes Lernfeld entfernen.
         */
		} elseif ($request->getAlnum("task") == "delete") {
			$commandHandler->deleteLfieldById($request->getInt("id"));
            \DJP\Services\Page::reload($config["url"]["client"]["admin"] . "?cmd=conflfield&response=6");
		} else {
            $view = new \DJP\View\ConfLfield();
            $lfieldList = $queryHandler->getLfieldList();
          
            $content = $view->render($lfieldList);
        }
        return $content;
    }

}