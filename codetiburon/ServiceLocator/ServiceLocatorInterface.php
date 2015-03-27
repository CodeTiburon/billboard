<?php
/**
 * Company: CodeTiburon
 * Date: 2014-03-27
 *
 * Service Locator Pattern
 */
namespace CodeTiburon\ServiceLocator;

interface ServiceLocatorInterface
{
    public function registerClass($name, $class, $isShared = true);

    public function registerInstance($name, $instance);

    public function unregister($name);

    public function get($name);
}