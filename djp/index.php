<?php

/**
 * Ein Autoloader wird im System registriert, um anhand des Namespaces
 * die PHP-Dateien automatisch includen zu koennen.
 */
include_once(dirname(__FILE__) . '/Services/Autoloader.php');
$autoloader = new \DJP\Services\Autoloader();


if (strnatcmp(phpversion(), '5.3.0') >= 0) {
    error_reporting(E_ALL & ~E_DEPRECATED);
} else {
    error_reporting(E_ALL);
}
ini_set("display_errors", 1);

$controller = new \DJP\Controller\Frontend();
die($controller->buildPageOutput());
