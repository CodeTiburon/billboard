<?php
/**
 * Company: CodeTiburon
 * Date: 2014-03-27
 *
 * Observer Pattern
 */
namespace CodeTiburon\EventManager;

interface EventManagerInterface
{
    public function subscribe($event, $callable);

    public function trigger($event, ...$params);
}