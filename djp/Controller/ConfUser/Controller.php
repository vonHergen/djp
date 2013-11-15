<?php

    namespace DJP\Controller\ConfUser;
    
    class Controller
    {        
        public function execute() 
        {
        $auth = \DJP\Services\Registry::getInstance()->getEntry("auth");
        $response = \DJP\Services\Registry::getInstance()->getEntry("response");
        $request = \DJP\Services\Registry::getInstance()->getEntry("request");
        $config = \DJP\Services\Registry::getInstance()->getEntry("config");
        $queryHandler = new \DJP\Model\Backend\ConfUser\DataHandler\QueryHandler();
        $commandHandler = new \DJP\Model\Backend\ConfUser\DataHandler\CommandHandler();

        /**
         * /F180/
         * 
         * Bestehenden Benutzer bearbeiten.
         */
        if ($request->getAlnum("task") == "edit") {
            if ($request->getInt("sent")) {
                $array = $request->getPostArray();
                unset($array["sent"]);
                unset($array["oldEmail"]);

                $isValid = new \DJP\Model\Backend\ConfUser\Specification\isValid($request->getRaw("oldEmail"));
                $isValid->setValues($array);

                if (!$isValid->validate()) {
                    $view = new \DJP\View\ConfUserEdit();
                    $content = $view->render($queryHandler->getUserById($request->getInt("id")), $queryHandler->getUserRoles(), $queryHandler->getEducationList(), true, $isValid->getErrors());
                }
                else {
                    $commandHandler->updateUserById($request->getInt("id"), $array);
                    $auth->reloadData();
                    \DJP\Services\Page::reload($config["url"]["client"]["admin"] . "?cmd=confuser&response=1");
                }
            }
            else {
                $view = new \DJP\View\ConfUserEdit();
                $content = $view->render($queryHandler->getUserById($request->getInt("id")), $queryHandler->getUserRoles(), $queryHandler->getEducationList(), true, false);
            }
        }
        /**
         * /F210/
         * 
         * Neuen Benutzer anlegen
         */
        elseif ($request->getAlnum("task") == "add") {
            if ($request->getInt("sent")) {
                $array = $request->getPostArray();
                unset($array["sent"]);

                $isValid = new \DJP\Model\Backend\ConfUser\Specification\isValid();
                $isValid->setAdd();
                $isValid->setValues($array);
                if (!$isValid->validate()) {
                    $array2 = array();
                    $view = new \DJP\View\ConfUserAdd();
                    foreach ($array as $key => $value) {
                        $array2[ucfirst($key)] = $value;
                    }
                    $content = $view->render($array2, $queryHandler->getUserRoles(), $queryHandler->getEducationList(), false, $isValid->getErrors());
                }
                else {
                    $commandHandler->addUser($array);
                    \DJP\Services\Page::reload($config["url"]["client"]["admin"] . "?cmd=confuser&response=2");
                }
            }
            else {
                $view = new \DJP\View\ConfUserEdit();
                $content = $view->render(false, $queryHandler->getUserRoles(), $queryHandler->getEducationList());
            }
        }
        /**
         * /F180/
         * 
         * Bestehenden Benutzer entfernen.
         */
        elseif ($request->getAlnum("task") == "delete") {
            $commandHandler->deleteUserById($request->getInt("id"));
            $auth->reloadData();
            \DJP\Services\Page::reload($config["url"]["client"]["admin"] . "?cmd=confuser&response=3");
        }
        else {
            $view = new \DJP\View\ConfUser();
            $content = $view->render($queryHandler->getUserList());
        }
        return $content;  
        }
    }