<?php
namespace Billboard\Controller;

use CodeTiburon\Controller\AbstractController;

class IndexController extends AbstractController
{
    public function indexAction()
    {
        $this->render('index/index.phtml', []);
    }

    public function testAction()
    {
        $this->json(['test' => 'test']);
    }
}