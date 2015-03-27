<?php
/**
 * Company: CodeTiburon
 * Date: 2014-03-27
 *
 * Service Locator Pattern
 */
namespace CodeTiburon\ServiceLocator;

trait ServiceLocatorAwareTrait
{
    protected $serviceLocator;

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;

        return $this;
    }
}