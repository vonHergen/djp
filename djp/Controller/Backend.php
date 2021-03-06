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
		$roles = $config["roles"];
		
        if ($auth->isLoggedIn() && intval($auth->getUserdata("Benutzer_Typ")) >= min($roles)) { {
                            
                switch ($request->getAlnum("cmd")) {
                    # Benutzerverwaltung ( Anlegen, Löschen, Bearbeiten )
                    case "confuser":
						if(intval($auth->getUserdata("Benutzer_Typ")) >= $roles["confuser"]) {
							$controllerCU = new \DJP\Controller\ConfUser\Controller();
							$content = $controllerCU->execute();
						} else {
							$controllerCI = new \DJP\Controller\ConfIndex\Controller();
							$content = $controllerCI->execute(true);
						}
                        break;
                    # Fächerverwaltung
                    case "confsubject":
						if(intval($auth->getUserdata("Benutzer_Typ")) >= $roles["confsubject"]) {
							$controllerCS = new \DJP\Controller\ConfSubject\Controller();
							$content = $controllerCS->execute();
						} else {
							$controllerCI = new \DJP\Controller\ConfIndex\Controller();
							$content = $controllerCI->execute(true);
						}
                        break;
                    # Lernferldverwaltung
                    case "conflfield":
						if(intval($auth->getUserdata("Benutzer_Typ")) >= $roles["conflfield"]) {
							$controllerCL = new \DJP\Controller\ConfLfield\Controller();
							$content = $controllerCL->execute();
						} else {
							$controllerCI = new \DJP\Controller\ConfIndex\Controller();
							$content = $controllerCI->execute(true);
						}
                        break;
                    # Bildungsgangverwaltung
					case "confeducation":
						if(intval($auth->getUserdata("Benutzer_Typ")) >= $roles["confeducation"]) {
							$controllerCE = new \DJP\Controller\ConfEducation\Controller();
							$content = $controllerCE->execute();
						} else {
							$controllerCI = new \DJP\Controller\ConfIndex\Controller();
							$content = $controllerCI->execute(true);
						}
						break;
					case "confrole": 
						if(intval($auth->getUserdata("Benutzer_Typ")) >= $roles["confrole"]) {
							$controllerCR = new \DJP\Controller\ConfRole\Controller();
							$content = $controllerCR->execute();
						} else {
							$controllerCI = new \DJP\Controller\ConfIndex\Controller();
							$content = $controllerCI->execute(true);
						}
						break;
					case "logout":
						$auth->logout();
						\DJP\Services\Page::reload($config["url"]["client"]["admin"]);
                    default:
                        $controllerCI = new \DJP\Controller\ConfIndex\Controller();
						$content = $controllerCI->execute();
                        break;
                }

                $view = new \DJP\View\Admin();
				$user["name"] = $auth->getUserdata("Vorname") . " " . $auth->getUserdata("Nachname");
				$user["role"] = $auth->getUserdata("Benutzer_Typ");
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
                if ($auth->isLoggedIn() && intval($auth->getUserdata("Benutzer_Typ")) >= min($roles)) {
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
