<?php
/**
 * Company: CodeTiburon
 * Date: 2014-03-27
 *
 * Service Configurator Pattern
 */
namespace CodeTiburon\ServiceLocator;

interface ConfiguratorInterface
{
    public function configure(ServiceLocatorInterface $serviceLocator);
}