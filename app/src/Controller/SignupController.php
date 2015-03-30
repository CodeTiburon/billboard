<?php
/**
 * Company: CodeTiburon
 * Date: 2014-03-27
 */
namespace Billboard\Controller;

use CodeTiburon\Controller\AbstractController;

class SignupController extends AbstractController
{
    protected $userService;

    public function __construct($userService)
    {
        $this->userService = $userService;
    }

    public function indexAction()
    {
        $this->render('signup/index.phtml', []);
    }

    public function submitAction()
    {
        $input = $this->getInput();
        $error = false;

        if (empty($login = trim($input['login']))) {
            $error = 'Login cannot be empty';
        } elseif ($this->userService->get($login)) {
            $error = 'The user with the same login already exists';
        } elseif (empty($password = $input['password'])) {
            $error = 'Password cannot be empty';
        } elseif ($input['password'] !== $input['password_re']) {
            $error = 'Passwords are not the same';
        }

        if ($error) {
            $this->json(['status' => false, 'error' => $error]);
        } elseif ($this->userService->signUp($login, $password)) {
            $this->json(['status' => true]);
        } else {
            $this->json(['status' => false, 'error' => 'Registration error happened']);
        }
    }
}