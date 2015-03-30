<?php
/**
 * Company: CodeTiburon
 * Date: 2014-03-27
 */
namespace Billboard\Controller;

use CodeTiburon\Controller\AbstractController;

class SigninController extends AbstractController
{
    protected $userService;

    public function __construct($userService)
    {
        $this->userService = $userService;
    }

    public function indexAction()
    {
        $this->render('signin/index.phtml', []);
    }

    public function cancelAction()
    {
        $this->userService->signOut();
        $this->redirect('/signin');
    }

    public function submitAction()
    {
        $input = $this->getInput();
        $error = false;

        if (empty($login = trim($input['login']))) {
            $error = 'Login cannot be empty';
        } elseif (empty($password = $input['password'])) {
            $error = 'Password cannot be empty';
        } elseif (!$this->userService->signIn($login, $password)) {
            $error = 'Invalid pair Login and Password';
        }

        if ($error) {
            $this->json(['status' => false, 'error' => $error]);
        } else {
            $this->json(['status' => true]);
        }
    }
}