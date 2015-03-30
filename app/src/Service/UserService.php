<?php
/**
 * Company: CodeTiburon
 * Date: 2014-03-30
 */
namespace Billboard\Service;

class UserService
{
    public $userMapper;

    public function __construct($userMapper)
    {
        $this->userMapper = $userMapper;
    }

    public function get($login)
    {
        return $this->userMapper->fetchOne(['login' => $login]);
    }

    public function signUp($login, $password)
    {
        if (!$this->userMapper->insert([
            'login' => $login,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'time_created' => gmdate('Y-m-d H:i:s'),
            'time_updated' => gmdate('Y-m-d H:i:s'),
        ])) {
            return false;
        };

        return $this->signIn($login, $password);
    }

    public function signIn($login, $password)
    {
        $user = $this->get($login);

        if (!$user || !password_verify($password, $user['password'])) {
            return false;
        }

        // Prevent session fixation
        session_regenerate_id(false);

        $_SESSION['login'] = $login;
        return true;
    }

    public function signOut()
    {
        unset($_SESSION['login']);
    }
}