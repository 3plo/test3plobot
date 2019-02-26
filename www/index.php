<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 26.02.2019
 * Time: 21:28
 */

require_once __DIR__ . '/../application/Autoloader.php';
define('ROOT', __DIR__ . DIRECTORY_SEPARATOR . '..');
$application = new \application\Application(
    require_once ROOT . DIRECTORY_SEPARATOR . 'application_config.php'
);
$application->run(
    $_SERVER['REQUEST_URI'],
    isset($_REQUEST) ? $_REQUEST : array(),
    isset($_SESSION) ? $_SESSION : array()
);