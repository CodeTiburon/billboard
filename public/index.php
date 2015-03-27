<?php

use CodeTiburon\ClassAutoloader\ClassAutoloader;
use CodeTiburon\ServiceLocator\ServiceLocator;

/**
 * Change current working directory to the project root
 */
chdir(dirname(__DIR__));

/**
 * Define globally application root directory path
 */
define('ROOT_PATH', getcwd());
define('APP_PATH', ROOT_PATH . '/app');

/**
 * Register ClassAutoloader
 */
require_once('./codetiburon/ClassAutoloader/ClassAutoloader.php');
$autoloader = (new ClassAutoloader())
    ->addNamespace('CodeTiburon', ROOT_PATH . '/codetiburon')
    ->addNamespace('Billboard', ROOT_PATH . '/app/src')
    ->register();

/**
 * Create Service Locator instance
 */
$serviceLocator = (new ServiceLocator())
    ->configure(new \Billboard\Configurator());

/**
 * Run Application
 */
$serviceLocator
    ->get('FrontController')
    ->dispatch();