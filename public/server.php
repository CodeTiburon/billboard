<?php

switch (php_sapi_name()) {
    case 'cli-server':
        $info = parse_url($_SERVER['REQUEST_URI']);

        if (file_exists(__DIR__ . "/{$info['path']}")) {
            return false;
        } else {
            include_once __DIR__ . "/index.php";
            return true;
        }
        break;

    case 'cli':
        exec('php -S localhost:9999 -t "' . __DIR__ . '" "' . __FILE__  .'"');
        break;

    default:
        echo '<h1>Error 403 - This script may be run only with CLI</h1>';
        break;
}