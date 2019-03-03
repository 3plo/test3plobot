<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 26.02.2019
 * Time: 21:58
 */

namespace application\routers;


use application\registers\ApplicationConfig;
use application\routers\exceptions\InvalidMethodException;
use application\routers\exceptions\MethodNotAllowedException;
use application\routers\exceptions\RouteNotFoundException;

class Router
{
    /**
     * @var array
     */
    private $rawRoutes = [];

    /**
     * @var array
     */
    private $routesTree = [];

    /**
     * @var array
     */
    private $allowedMethods = [];

    /**
     * Router constructor.
     */
    public function __construct()
    {
        $this->allowedMethods = ApplicationConfig::getConfigValue('router_allowed_http_methods');
    }

    /**
     * Add new route to list of available routes
     *
     * @param array $methodList
     * @param string $route
     * @param string $action
     * @throws InvalidMethodException
     */
    public function addRoute(array $methodList, string $route, string $action)
    {
        if (array_diff($methodList, $this->allowedMethods)) {
            throw new InvalidMethodException('Method:' . implode(', ', $methodList) . ' is not valid');
        }
        $methods = [];
        foreach ($methodList as $value) {
            $methods[$value] = $action;
        }
        $this->rawRoutes[] = ['route' => $route, 'method' => $methods];
    }

    /**
     * @param string $method
     * @param string $uri
     * @return array
     * @throws MethodNotAllowedException
     * @throws RouteNotFoundException
     */
    public function findRoute(string $method, string $uri): array
    {
        if (empty($this->routesTree)) {
            $this->routesTree = $this->parseRoutes($this->rawRoutes);
        }
        $search = $this->normalize($uri);
        $node = $this->routesTree;
        $params = [];
        //loop every segment in request url, compare it, collect parameters names and values
        foreach ($search as $v) {
            if (isset($node[$v['use']])) {
                $node = $node[$v['use']];
            } elseif (isset($node['*'])) {
                $node = $node['*'];
                $params[$node['name']] = $v['name'];
            } elseif (isset($node['?'])) {
                $node = $node['?'];
                $params[$node['name']] = $v['name'];
            } else {
                throw new RouteNotFoundException('Route for uri: ' . $uri . ' was not found');
            }
        }
        while (!isset($node['exec']) && isset($node['?'])) {
            $node = $node['?'];
        }
        if (isset($node['exec'])) {
            if (!isset($node['exec']['method'][$method]) && !isset($node['exec']['method']['any'])) {
                throw new MethodNotAllowedException('Method: ' . $method . ' is not allowed for this route');
            }
            return [
                'route' => $node['exec']['route'],
                'method' => $method,
                'action' => $node['exec']['method'][$method],
                'params' => $params
            ];
        } else {
            throw new RouteNotFoundException('Route for uri: ' . $uri . ' was not found');
        }
    }

    /**
     * @return array
     */
    public function dump(): array
    {
        if (empty($this->routesTree)) {
            $this->routesTree = $this->parseRoutes($this->rawRoutes);
        }
        return $this->routesTree;
    }

    /**
     * @param array $routesTree
     * @param bool $reset
     */
    public function load(array $routesTree, bool $reset = false)
    {
        if (empty($this->routesTree) || $reset) {
            $this->routesTree = $routesTree;
        }
    }

    /**
     * @param $route
     * @return array
     */
    protected function normalize($route)
    {
        if (mb_substr($route, 0, 1) != '/') {
            $route = '/' . $route;
        }
        if (mb_substr($route, -1, 1) == '/') {
            $route = substr($route, 0, -1);
        }
        $explodeResult = explode('/', $route);
        $explodeResult[0] = '/';
        $normalizeResult = [];
        foreach ($explodeResult as $value) {
            if (!$value) {
                continue;
            }
            if (strpos($value, '?}') !== false) {
                $normalizeResult[] = ['name' => explode('?}', mb_substr($value, 1))[0], 'use' => '?'];
            } elseif (strpos($value, '}') !== false) {
                $normalizeResult[] = ['name' => explode('}', mb_substr($value, 1))[0], 'use' => '*'];
            } else {
                $normalizeResult[] = ['name' => $value, 'use' => $value];
            }
        }
        return $normalizeResult;
    }

    /**
     * @param $routes
     * @return array
     */
    protected function parseRoutes($routes)
    {
        $tree = [];
        foreach ($routes as $route) {
            $node = &$tree;
            foreach ($this->normalize($route['route']) as $segment) {
                if (!isset($node[$segment['use']])) {
                    $node[$segment['use']] = ['name' => $segment['name']];
                }
                $node = &$node[$segment['use']];
            }
            //node exec can exists only if a route is already added.
            //This happens when a route is added more than once with different methods.
            if (isset($node['exec'])) {
                $node['exec']['method'] = array_merge($node['exec']['method'], $route['method']);
            } else {
                $node['exec'] = $route;
            }
            $node['name'] = $segment['name'];
        }
        return $tree;
    }
}