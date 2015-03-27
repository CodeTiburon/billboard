<?php
/**
 * Service Configurator Pattern Implementation
 */
namespace Billboard;

use CodeTiburon\ServiceLocator\ConfiguratorInterface;
use CodeTiburon\ServiceLocator\ServiceLocatorInterface;

class Configurator implements ConfiguratorInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * Configure services and application
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function configure(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;

        // Apply configurations
        $this->configurePhp();
        $this->configureControllers();
        $this->configureRoutes();
        $this->configureViews();
        $this->configureDatabase();
    }

    /**
     * Configure PHP settings
     */
    public function configurePhp()
    {
        $settings = include APP_PATH . '/config/phpsettings.php';

        if ($settings && is_array($settings)) {
            foreach ($settings as $key => $val) {
                ini_set($key, $val);
            }
        }
    }

    /**
     * Configure Controller classes
     */
    public function configureControllers()
    {
        $this
            ->serviceLocator
            ->registerClass('IndexController', 'Billboard\Controller\IndexController');
    }

    /**
     * Configure Views path
     */
    public function configureViews()
    {
        $this
            ->serviceLocator
            ->get('ViewResolver')
            ->addPath(APP_PATH . '/views')
            ->setLayout(APP_PATH . '/views/layout.phtml');
    }

    /**
     * Configure Database connection
     */
    public function configureDatabase()
    {
        // TODO: Database configurations
    }

    /**
     * Configure Application Routes
     */
    public function configureRoutes()
    {
        $routes = include APP_PATH . '/config/routes.php';

        if ($routes && is_array($routes)) {
            $router = $this->serviceLocator->get('Router');

            foreach ($routes as $path => list($controller, $action)) {
                $router->addRoute($path, $controller, $action);
            }
        }
    }
}