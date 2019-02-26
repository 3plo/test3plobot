<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 26.02.2019
 * Time: 22:14
 */

namespace application;


class Autoloader
{
    public function loadClass($class)
    {
        $rootDir = __DIR__ . '/../';
        $path = $rootDir . $class . '.php';
        if (!class_exists($class))
        {
            if (file_exists($path))
            {
                require_once($path);
            }
        }
    }
}
spl_autoload_register([new Autoloader(), 'loadClass']);