<?php
/**
 * Company: CodeTiburon
 * Date: 2014-03-27
 *
 * FrontController Pattern Implementation
 */
namespace CodeTiburon\FrontController;

use CodeTiburon\EventManager\EventManager;
use CodeTiburon\Controller\ControllerInterface;
use CodeTiburon\Exception\ControllerNotFoundException;
use CodeTiburon\Exception\RouteNotFoundException;
use CodeTiburon\Exception\ServiceNotFoundException;
use CodeTiburon\Router\Router;
use CodeTiburon\ServiceLocator\ServiceLocator;

class FrontController implements FrontControllerInterface
{
    /**
     * @var ServiceLocator
     */
    public $serviceLocator;

    /**
     * @var EventManager
     */
    public $eventManager;

    public function __construct($serviceLocator, $eventManager)
    {
        $this->serviceLocator = $serviceLocator;
        $this->eventManager = $eventManager;
    }

    /**
     * Dispatch the request to the controller action
     *
     * @param string|null $uri
     * @throws ControllerNotFoundException
     * @throws RouteNotFoundException
     * @throws ServiceNotFoundException
     */
    public function dispatch($uri = null)
    {
        /** @var Router $router */
        $router = $this->serviceLocator->get('Router');
        $eventManager = $this->eventManager;

        if (null === $uri) {
            $uri = strtolower($_SERVER['REQUEST_URI']);
        }

        // Try to find appropriate route
        $eventManager->trigger('BEFORE_ROUTE_MATCH', $uri);
        $route = $router->match($uri);
        $eventManager->trigger('AFTER_ROUTE_MATCH', $uri);

        if (!$route) {
            $this->eventManager->trigger('ROUTE_NOT_FOUND', $uri);
            throw new RouteNotFoundException($uri);
        }

        $eventManager->trigger('BEFORE_CONTROLLER_RESOLVE', $route);

        try {
            // Resolve controller as a service
            $controller = $this->serviceLocator->get($route['controller']);
        } catch (ServiceNotFoundException $ex) {
            $this->eventManager->trigger('CONTROLLER_NOT_FOUND', $route['controller']);
            throw new ControllerNotFoundException($uri);
        }

        $eventManager->trigger('AFTER_CONTROLLER_RESOLVE', $route);

        if (!($controller instanceof ControllerInterface)) {
            throw new \RuntimeException('Controller should be an instance of CodeTiburon\Controller\ControllerInterface');
        }

        // Dispatch to controller action
        $eventManager->trigger('BEFORE_CONTROLLER_DISPATCH', $route);
        $controller->dispatch($route['action'], $route['matches']);
        $eventManager->trigger('AFTER_CONTROLLER_DISPATCH', $route);
    }


}