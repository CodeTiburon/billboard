<?php
/**
 * Company: CodeTiburon
 * Date: 2014-03-30
 *
 * Observer Subscriber Pattern implementation
 */
namespace CodeTiburon\EventManager;

interface EventSubscriberInterface
{
    public function subscribe(EventManagerInterface $eventManager);
}