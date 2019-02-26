<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 26.02.2019
 * Time: 22:00
 */

namespace application\registers;


class ApplicationConfig
{
    /**
     * @var array
     */
    private static $config = [];

    /**
     * @param array $config
     */
    public static function init(array $config)
    {
        if (empty(self::$config)) {
            self::$config = $config;
        }
    }

    /**
     * @return array
     */
    public static function getConfig(): array
    {
        return self::$config;
    }

    /**
     * @param string $paramTitle
     * @return mixed
     */
    public static function getConfigValue(string $paramTitle)
    {
        return self::$config[$paramTitle];
    }
}