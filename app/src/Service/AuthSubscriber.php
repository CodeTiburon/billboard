<?php
/**
 * Company: CodeTiburon
 * Date: 2014-03-27
 */
namespace Billboard\Service;

use CodeTiburon\EventManager\EventManagerInterface;
use CodeTiburon\EventManager\EventSubscriberInterface;

class AuthSubscriber implements EventSubscriberInterface
{
    public $userService;
    public $serviceLocator;

    public function __construct($serviceLocator, $userService)
    {
        $this->serviceLocator = $serviceLocator;
        $this->userService = $userService;
    }

    public function subscribe(EventManagerInterface $eventManager)
    {
        $eventManager->subscribe('BEFORE_ROUTE_MATCH', [$this, 'onBeforeRoute']);
    }

    public function onBeforeRoute($uri)
    {
        $isAnonPage = preg_match('#^/(?:signin|signup)#', $uri);

        if (empty($_SESSION['login'])) {
            if (!$isAnonPage) {
                $this->toSignin();
            } else {
                $this->serviceLocator->registerInstance('User', null);
            }
        } else {
            $user = $this->userService->get($_SESSION['login']);

            if (!$user) {
                session_regenerate_id(true);
                $this->toSignin();
            } else {
                $this->serviceLocator->registerInstance('User', $user);

                if ($isAnonPage) {
                    $this->toHome();
                }
            }
        }
    }

    public function toSignin()
    {
        header('Location: /signin');
        exit();
    }

    public function toHome()
    {
        header('Location: /');
        exit();
    }
}