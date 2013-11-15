<?php

namespace DJP\Controller\ConfSubject;

class Controller
{

    public function __construct()
    {
        
    }

    public function execute()
    {
        $auth = \DJP\Services\Registry::getInstance()->getEntry("auth");
        $response = \DJP\Services\Registry::getInstance()->getEntry("response");
        $request = \DJP\Services\Registry::getInstance()->getEntry("request");
        $config = \DJP\Services\Registry::getInstance()->getEntry("config");
        $queryHandler = new \DJP\Model\Backend\ConfSubject\DataHandler\QueryHandler();
        $commandHandler = new \DJP\Model\Backend\ConfSubject\DataHandler\CommandHandler();
        
        if($request->getAlnum("task") == "edit") {
            if($request->getInt("sent")) {
                $array = $request->getPostArray();
                unset($array["sent"]);
                
                $isValid = new \DJP\Model\Backend\ConfSubject\Specification\isValid();
                $isValid->setValues($array);
				
				if (!$isValid->validate()) {
                    $view = new \DJP\View\ConfSubjectEdit();
                    $content = $view->render($queryHandler->getSubjectById($request->getInt("id")), true, $isValid->getErrors());
                }
                else {
                    $commandHandler->updateSubjectById($request->getInt("id"), $array);
                    $auth->reloadData();
                    \DJP\Services\Page::reload($config["url"]["client"]["admin"] . "?cmd=confsubject&response=4");
                }

            } else {
                $view = new \DJP\View\ConfSubjectEdit();
                $content = $view->render($queryHandler->getSubjectById($request->getInt("id")), true, false);
			}
        } elseif ($request->getAlnum("task") == "add") {
			if($request->getInt("sent")) {
				$array = $request->getPostArray();
				unset($array["sent"]);
				
				$isValid = new \DJP\Model\Backend\ConfSubject\Specification\isValid();
				$isValid->setValues($array);
				
				if (!$isValid->validate()) {
					$array2 = array();
                    $view = new \DJP\View\ConfSubjectEdit();
                    foreach ($array as $key => $value) {
                        $array2[ucfirst($key)] = $value;
                    }
                   $content = $view->render($array2, false, $isValid->getErrors());
                }
                else {
                    $commandHandler->addSubject($array);
                    \DJP\Services\Page::reload($config["url"]["client"]["admin"] . "?cmd=confsubject&response=5");
                }
			} else {
				$view = new \DJP\View\ConfSubjectEdit();
				$content = $view->render();
			}
		} elseif ($request->getAlnum("task") == "delete") {
			$commandHandler->deleteSubjectById($request->getInt("id"));
            \DJP\Services\Page::reload($config["url"]["client"]["admin"] . "?cmd=confsubject&response=6");
		} else {
            $view = new \DJP\View\ConfSubject();
            $subjectList = $queryHandler->getSubjectList();
          
            $content = $view->render($subjectList);
        }
        return $content;
    }

}

