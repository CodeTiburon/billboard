<?php
/**
 * Company: CodeTiburon
 * Date: 2014-03-27
 *
 * Service Locator Aware Trait
 */
namespace CodeTiburon\ServiceLocator;

use CodeTiburon\Common\SingletonTrait;
use CodeTiburon\DependencyInjection\DependencyInjection;
use CodeTiburon\DependencyInjection\DependencyInjectionInterface;
use CodeTiburon\Exception\ServiceNotFoundException;

class ServiceLocator implements ServiceLocatorInterface
{
    use SingletonTrait;

    /**
     * @var DependencyInjection
     */
    protected $di;

    /**
     * @var array
     */
    protected $instances = [];

    /**
     * @var array
     */
    protected $classes = [
        'EventManager'    => ['shared' => true, 'class' => 'CodeTiburon\EventManager\EventManager'],
        'FrontController' => ['shared' => true, 'class' => 'CodeTiburon\FrontController\FrontController'],
        'Router'          => ['shared' => true, 'class' => 'CodeTiburon\Router\Router'],
        'ViewResolver'    => ['shared' => true, 'class' => 'CodeTiburon\ViewResolver\ViewResolver'],
    ];

    /**
     * @var array
     */
    protected $factories = [];

    /**
     * @var array TODO: Aliases functionality will be implemented later
     */
    protected $aliases = [];


    public function __construct()
    {
        $this->registerInstance('ServiceLocator', $this);

        $this->classes = array_change_key_case($this->classes, CASE_LOWER);
        $this->factories = array_change_key_case($this->classes, CASE_LOWER);
        $this->aliases = array_change_key_case($this->classes, CASE_LOWER);
    }

    /**
     * @return DependencyInjection
     */
    public function getDi()
    {
        if (null === $this->di) {
            $this->setDi(new DependencyInjection($this));
        }

        return $this->di;
    }

    /**
     * @param DependencyInjectionInterface $di
     * @return $this
     */
    public function setDi(DependencyInjectionInterface $di)
    {
        $this->registerInstance('DependencyInjection', $this->di = $di);

        return $this;
    }

    /**
     * Register class name
     *
     * @param string $name
     * @param string $class
     * @param bool $isShared
     * @return $this
     */
    public function registerClass($name, $class, $isShared = true)
    {
        $this->classes[$this->canonicalize($name)] = [
            'class' => $class,
            'shared' => $isShared
        ];

        return $this;
    }

    /**
     * Register Factory (instance of FactoryInterface or a callback)
     *
     * @param string $name
     * @param string|callable $factory
     * @param bool $isShared
     * @return $this
     */
    public function registerFactory($name, $factory, $isShared = true)
    {
        $this->factories[$name = $this->canonicalize($name)] = [
            'factory' => $factory,
            'shared' => $isShared
        ];

        return $this;
    }

    /**
     * Register existing instance
     *
     * @param string $name
     * @param object $instance
     * @return $this
     */
    public function registerInstance($name, $instance)
    {
        $this->instances[$this->canonicalize($name)] = $instance;

        return $this;
    }

    /**
     * Unregister the service
     *
     * @param $name
     * @return $this
     */
    public function unregister($name)
    {
        $name = $this->canonicalize($name);

        if (isset($this->classes[$name])) {
            unset($this->classes[$name]);
        }

        if (isset($this->instances[$name])) {
            unset($this->instances[$name]);
        }

        return $this;
    }

    /**
     * @param ConfiguratorInterface $configurator
     * @return $this
     */
    public function configure(ConfiguratorInterface $configurator)
    {
        $configurator->configure($this);
        return $this;
    }

    /**
     * Get registered servie by name
     *
     * @param $name
     * @return bool|object
     * @throws ServiceNotFoundException
     */
    public function get($name)
    {
        $name = $this->canonicalize($name);

        if (isset($this->instances[$name])) {
            return $this->instances[$name];
        }

        static $shortCircuit = [];

        if (isset($shortCircuit[$name])) {
            throw new \RuntimeException('The instantiation short cicuit detected');
        }

        $shortCircuit[$name] = true;
        $instance = $shared = false;

        try {
            if (isset($this->classes[$name])) {
                // Invokable class

                $instance = $this->getDi()->instantiate($this->classes[$name]['class']);
                $shared = $this->classes[$name]['shared'];

            } elseif (isset($this->factories[$name])) {
                // Factory building

                $factory = $this->factories[$name]['factory'];

                if (is_subclass_of($factory, 'CodeTiburon\Service\FactoryInterface')) {
                    $factory = [$this->getDi()->instantiate($factory), 'create'];
                }

                if (is_callable($factory)) {
                    $instance = $factory();
                    $shared = $this->factories[$name]['shared'];
                }
            }

            if (false === $instance) {
                throw new ServiceNotFoundException($name);
            }

            if ($shared) {
                $this->instances[$name] = $instance;
            }

            if ($instance instanceof ServiceLocatorAwareInterface) {
                $instance->setServiceLocator($this);
            }

            return $instance;
        } finally {
            unset($shortCircuit[$name]);
        }
    }

    /**
     * Simplify the name
     *
     * @param $name
     * @return string
     */
    protected function canonicalize($name)
    {
        return strtolower(trim($name));
    }
}