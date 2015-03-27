<?php
/**
 * Company: CodeTiburon
 * Date: 2014-03-27
 *
 * Service Configurator Pattern Implementation
 */
namespace Billboard;

use CodeTiburon\Common\Db;
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
        $this->configureMappers();
        $this->configureServices();
        $this->configureRoutes();
        $this->configureViews();
        $this->configureDatabase();

        // Attach Listeners to application events
        $this->subscribeListeners();
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
            ->registerClass('IndexController', 'Billboard\Controller\IndexController')
            ->registerClass('SigninController', 'Billboard\Controller\SigninController')
            ->registerClass('SignupController', 'Billboard\Controller\SignupController');
    }

    /**
     * Configure Mapper classes
     */
    public function configureMappers()
    {
        $this
            ->serviceLocator
            ->registerClass('BulletinMapper', 'Billboard\Model\BulletinMapper')
            ->registerClass('CategoryMapper', 'Billboard\Model\CategoryMapper')
            ->registerClass('CommentMapper', 'Billboard\Model\CommentMapper')
            ->registerClass('UserMapper', 'Billboard\Model\UserMapper');
    }

    /**
     * Configure Service classes
     */
    public function configureServices()
    {
        $this
            ->serviceLocator
            ->registerClass('AuthenticationListener', 'Billboard\Service\AuthenticationListener');
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
        $dbparams = include APP_PATH . '/config/db.php';

        Db::getInstance(
            $dbparams['host'],
            $dbparams['db'],
            $dbparams['user'],
            $dbparams['pass']
        );
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

    public function subscribeListeners()
    {
        $this
            ->serviceLocator
            ->get('EventManager')
            ->subscribe('BEFORE_ROUTE_MATCH', [
                $this->serviceLocator->get('AuthenticationListener'),
                'onBeforeRoute'
            ]);
    }
}