<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 26.02.2019
 * Time: 22:11
 */

namespace application\registers;


class SessionRegister
{
    /**
     * @var array
     */
    private static $register = [];

    /**
     * @param array $register
     */
    public static function init(array $register = [])
    {
        if (empty(self::$register)) {
            self::$register = $register;
        }
    }

    /**
     * ініціалізація реєстру на основі сесії
     */
    public static function initFromSession()
    {
        if (empty(self::$register)) {
            self::$register = $_SESSION;
        }
    }

    /**
     * @return array
     */
    public static function getRegister(): array
    {
        return self::$register;
    }

    /**
     * @param string $paramTitle
     * @return mixed
     */
    public static function getRegisterValue(string $paramTitle)
    {
        return self::$register[$paramTitle];
    }

    /**
     * @param string $title
     * @param mixed $value
     */
    public static function setRegisterValue(string $title, $value)
    {
        self::$register[$title] = $value;
    }
}