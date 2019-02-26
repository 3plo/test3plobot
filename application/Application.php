<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 26.02.2019
 * Time: 21:57
 */

namespace application;

use application\registers\ApplicationConfig;
use application\registers\ApplicationRegister;
use application\registers\SessionRegister;

class Application
{
    /**
     * Application constructor.
     * @param array $configData
     */
    public function __construct(array $configData)
    {
        ApplicationConfig::init($configData);
        ApplicationRegister::init();
        SessionRegister::initFromSession();
    }


    public function run()
    {
        echo var_dump(ApplicationConfig::getConfig());
    }
}