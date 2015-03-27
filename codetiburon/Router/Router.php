<?php
/**
 * Company: CodeTiburon
 * Date: 2014-03-27
 *
 * Router Pattern Implementation
 */
namespace CodeTiburon\Router;

class Router implements  RouterInterface
{
    protected $routes = [];

    /**
     * @param string $route
     * @param $controller
     * @param $action
     */
    public function addRoute($route, $controller, $action)
    {
        $this->routes[] = [
            'route' => '#^' . trim($route, '/') . '$#i',
            'controller' => $controller,
            'action' => $action
        ];
    }

    /**
     * @param string $uri
     * @return bool|array
     */
    public function match($uri)
    {
        $matches = [];

        foreach ($this->routes as $route) {
            if (preg_match($route['route'], $uri, $matches)) {
                $route['matches'] = $matches;
                return $route;
            }
        }

        return false;
    }
}