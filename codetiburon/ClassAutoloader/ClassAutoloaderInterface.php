<?php
/**
 * Company: CodeTiburon
 * Date: 2014-03-27
 *
 * Class Autoloader Interface
 */
namespace CodeTiburon\ClassAutoloader;

interface ClassAutoloaderInterface
{
    public function register();

    public function unregister();

    public function addNamespace($prefix, $baseDir);

    public function loadClass($class);
}