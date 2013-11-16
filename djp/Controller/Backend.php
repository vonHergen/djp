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
                    # Benutzerverwaltung ( Anlegen, Löschen, Bearbeiten )
                    case "confuser":
                        $controllerCU = new \DJP\Controller\ConfUser\Controller();
                        $content = $controllerCU->execute();
                        break;
                    # Fächerverwaltung
                    case "confsubject":
                        $controllerCS = new \DJP\Controller\ConfSubject\Controller();
                        $content = $controllerCS->execute();
                        break;
                    # Lernferldverwaltung
                    case "conflfield":
                        $controllerCL = new \DJP\Controller\ConfLfield\Controller();
                        $content = $controllerCL->execute();
                        break;
                    # Bildungsgangverwaltung
					case "confeducation":
						$controllerCE = new \DJP\Controller\ConfEducation\Controller();
						$content = $controllerCE->execute();
						break;
					case "logout":
						$auth->logout();
						\DJP\Services\Page::reload($config["url"]["client"]["admin"]);
                    default:
                        $content = "Willkommen im Administrationsbereich der didaktischen Jahresplanung";
                        break;
                }

                $view = new \DJP\View\Admin();
				$user = $auth->getUserdata("Vorname") . " " . $auth->getUserdata("Nachname");
                $response->setOutputByKey("DJP", $view->render($content, $user ,$request->getInt("response")));
            }
        }
        else {
            /**
             * /F70/
             * 
             * Pruefung der Benutzerinformationen
             */
            if ($request->getInt("sent")) {
                $pass = trim(md5($request->getAlnum('password')));
                $auth->login($request->getRaw('email'), $pass);

                # Benutzer_Typ = 3 , entspricht der Admin-Rolle
                # Benutzer_Typ = 2 , entspricht der Bildungsgangleiter-Rolle
                # Benutzer_Typ = 1 , entspricht der Vertreter-Rolle
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
