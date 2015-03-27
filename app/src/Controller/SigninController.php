<?php
/**
 * Company: CodeTiburon
 * Date: 2014-03-27
 */
namespace Billboard\Controller;

use CodeTiburon\Controller\AbstractController;

class SigninController extends AbstractController
{
    public function indexAction()
    {
        $this->render('signin/index.phtml', []);
    }

    public function submitAction()
    {

    }
}