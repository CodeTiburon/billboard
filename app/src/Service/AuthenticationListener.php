<?php
namespace Billboard\Service;

class AuthenticationListener
{
    public $userMapper;
    public $serviceLocator;

    public function __construct($serviceLocator, $userMapper)
    {
        $this->serviceLocator = $serviceLocator;
        $this->userMapper = $userMapper;
    }

    public function onBeforeRoute($uri)
    {
        if (empty($_SESSION['user_id'])) {
            if ($uri !== '/signin' && $uri !== '/signup') {
                $this->toUserLogin();
            }
        } else {
            $user = $this->userMapper->find($_SESSION['user_id']);

            if (!$user) {
                session_regenerate_id(true);
                $this->toUserLogin();
            } else {
                $this->serviceLocator->registerInstance('User', $user);
            }
        }
    }

    public function toUserLogin()
    {
        header('Location: /signin');
        exit();
    }
}