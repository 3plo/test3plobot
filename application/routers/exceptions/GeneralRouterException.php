<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 27.02.2019
 * Time: 23:01
 */

namespace application\routers\exceptions;


abstract class GeneralRouterException extends \Exception
{
    public function __construct($message = "")
    {
        parent::__construct($message, 0, null);
    }
}