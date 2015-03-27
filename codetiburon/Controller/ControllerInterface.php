<?php
/**
 * Company: CodeTiburon
 * Date: 2014-03-27
 */
namespace CodeTiburon\Controller;

interface ControllerInterface
{
    public function dispatch($action, $params);
}