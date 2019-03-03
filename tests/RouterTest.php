<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 27.02.2019
 * Time: 23:15
 */

namespace tests;

use application\registers\ApplicationConfig;
use application\routers\Router;
use PHPUnit\Framework\TestCase;

final class RouterTest extends TestCase
{

    /**
     * @var Router
     */
    private $router;

    public function setUp()
    {
        require_once __DIR__ . '/test.php';
        ApplicationConfig::init(
            require ROOT . DIRECTORY_SEPARATOR . 'application_config.php'
        );
        $this->router = new Router();
        $this->router->addRoute(['get'], '/', 'home');
        $this->router->addRoute(['get'], '/{id}', 'home-id');
        $this->router->addRoute(['get'], '/{id?}', 'home-opt-id');
        $this->router->addRoute(['get'], '/user', 'user');
        $this->router->addRoute(['get'], '/user/{id}', 'user-id');
        $this->router->addRoute(['post'], '/user/{id}', 'user-id-post');
        $this->router->addRoute(['get'], '/user/{id}/edit', 'user-id-edit');
        $this->router->addRoute(['get'], '/user/id/{name}/{group}/{ord?}', 'user-id-name-group-opt-ord');
    }


    public function testRouter()
    {
        $this->setUp();
        $result = $this->router->findRoute('get', '/');
        $this->assertEquals('home', $result['action']);
        $result = $this->router->findRoute('get', '/user');
        $this->assertEquals('user', $result['action']);
        $result = $this->router->findRoute('get', '/user/1');
        $this->assertEquals('user-id', $result['action']);
        $result = $this->router->findRoute('post', '/user/1');
        $this->assertEquals('user-id-post', $result['action']);
        $result = $this->router->findRoute('get', '/user/1/edit');
        $this->assertEquals('user-id-edit', $result['action']);
        $result = $this->router->findRoute('get', '/user/id/gatakka/admin/');
        $this->assertEquals('user-id-name-group-opt-ord', $result['action']);
        $result = $this->router->findRoute('get', '/user/id/gatakka/admin/2');
        $this->assertEquals('user-id-name-group-opt-ord', $result['action']);
    }


    function testInvalidRoute()
    {
//        $this->setExpectedException(
//            '\PGF\Router\Exceptions\RouteNotFoundException'
//        );
//        $this->router->findRoute('get', '/user/id/billy/admin/2/3');
    }


    function testInvalidMethod()
    {
//        $this->setExpectedException(
//            '\PGF\Router\Exceptions\MethodNotAllowedException'
//        );
//        $this->router->findRoute('post', '/user/id/billy/admin/2');
    }
}