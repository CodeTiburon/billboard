<?php
/**
 * Company: CodeTiburon
 * Date: 2014-03-27
 *
 * Service Locator Pattern
 */
namespace CodeTiburon\ServiceLocator;

interface ServiceLocatorAwareInterface
{
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator);
}