<?php
/**
 * Company: CodeTiburon
 * Date: 2014-03-27
 *
 * Router Pattern
 */
namespace CodeTiburon\Router;

interface RouterInterface
{
    public function addRoute($route, $controller, $action);

    public function match($uri);
}