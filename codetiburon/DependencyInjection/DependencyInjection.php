<?php
/**
 * Company: CodeTiburon
 * Date: 2014-03-27
 *
 * DependencyInjection Pattern Implementation
 */
namespace CodeTiburon\DependencyInjection;

use CodeTiburon\Exception\ServiceNotFoundException;
use CodeTiburon\ServiceLocator\ServiceLocatorInterface;

class DependencyInjection implements DependencyInjectionInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function __construct(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Create an instance of the class
     * It reads the paramers names and Type hints and tries to get appropriate service from the ServiceLocator
     *
     * @param string $class
     * @return object
     * @throws ServiceNotFoundException
     */
    public function instantiate($class)
    {
        $reflectionClass = new \ReflectionClass($class);

        $constructor = $reflectionClass->getConstructor();

        if (null === $constructor) {
            return $reflectionClass->newInstance();
        }

        $parameters = $constructor->getParameters();
        $arguments = [];

        foreach ($parameters as $param) {
            $service = false;

            $varName = $param->getName();
            $className = $param->getClass() ? $param->getClass()->getName() : null;

            foreach ([$varName, $className] as $name) {
                if (!$name) {
                    continue;
                }

                // We are trying to create a service
                try {
                    $service = $this->serviceLocator->get($name);
                    break;
                } catch (ServiceNotFoundException $ex) {

                }
            }

            if (false === $service) {
                throw new ServiceNotFoundException($param->getName());
            }

            $arguments[] = $service;
        }

        return $reflectionClass->newInstanceArgs($arguments);
    }
}