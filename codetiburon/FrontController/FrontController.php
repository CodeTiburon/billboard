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

        if (null === $uri) {
            $uri = strtolower(trim($_SERVER['REQUEST_URI'], '/'));
        }

        // Try to find appropriate route
        $this->eventManager->trigger('BEFORE_ROUTE_MATCH');
        $route = $router->match($uri);
        $this->eventManager->trigger('AFTER_ROUTE_MATCH');

        if (!$route) {
            $this->eventManager->trigger('ROUTE_NOT_FOUND');
            throw new RouteNotFoundException($uri);
        }

        $this->eventManager->trigger('BEFORE_CONTROLLER_RESOLVE');

        try {
            // Resolve controller as a service
            $controller = $this->serviceLocator->get($route['controller']);
        } catch (ServiceNotFoundException $ex) {
            $this->eventManager->trigger('CONTROLLER_NOT_FOUND');
            throw new ControllerNotFoundException($uri);
        }

        $this->eventManager->trigger('AFTER_CONTROLLER_RESOLVE');

        if (!($controller instanceof ControllerInterface)) {
            throw new \RuntimeException('Controller should be an instance of CodeTiburon\Controller\ControllerInterface');
        }

        // Dispatch to controller action
        $this->eventManager->trigger('BEFORE_CONTROLLER_DISPATCH');
        $controller->dispatch($route['action'], $route['matches']);
        $this->eventManager->trigger('AFTER_CONTROLLER_DISPATCH');
    }


}