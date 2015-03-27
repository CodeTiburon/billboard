<?php
/**
 * Company: CodeTiburon
 * Date: 2014-03-27
 */
namespace Billboard\Controller;

use CodeTiburon\Controller\AbstractController;

class SignupController extends AbstractController
{
    public function indexAction()
    {
        $this->render('signup/index.phtml', []);
    }
}