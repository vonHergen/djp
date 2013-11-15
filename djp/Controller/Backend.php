<?php

namespace DJP\Controller;

class Backend
{

    public function buildPageOutput()
    {
        session_start();

        $error = false;
        $auth = \DJP\Services\Registry::getInstance()->getEntry("auth");
        $response = \DJP\Services\Registry::getInstance()->getEntry("response");
        $request = \DJP\Services\Registry::getInstance()->getEntry("request");
        $config = \DJP\Services\Registry::getInstance()->getEntry("config");

        if ($auth->isLoggedIn() && intval($auth->getUserdata("Benutzer_Typ")) > 2) { {
                switch ($request->getAlnum("cmd")) {
                    case "confuser":
                        $controllerCU = new \DJP\Controller\ConfUser\Controller();
                        $content = $controllerCU->execute();
                        break;
                    case "confsubject":
                        $controllerCS = new \DJP\Controller\ConfSubject\Controller();
                        $content = $controllerCS->execute();
                        break;
                    case "conflfield":
                        $controllerCL = new \DJP\Controller\ConfLfield\Controller();
                        $content = $controllerCL->execute();
                        break;
					case "logout":
						$auth->logout();
						\DJP\Services\Page::reload($config["url"]["client"]["admin"]);
                    default:
                        $content = false;
                        break;
                }

                $view = new \DJP\View\Admin();
                $response->setOutputByKey("DJP", $view->render($content, $request->getInt("response")));
            }
        }
        else {
            if ($request->getInt("sent")) {
                $pass = trim(md5($request->getAlnum('password')));
                $auth->login($request->getRaw('email'), $pass);

                if ($auth->isLoggedIn() && intval($auth->getUserdata("Benutzer_Typ")) > 2) {
                    \DJP\Services\Page::reload($config["url"]["client"]["admin"]);
                }
                $error = true;
            }

            $view = new \DJP\View\Login();
            $response->setOutputByKey("DJP", $view->render($error));
        }

        return $response->getOutputByKey("DJP");
    }

}
