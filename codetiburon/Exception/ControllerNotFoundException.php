<?php
/**
 * Company: CodeTiburon
 * Date: 2014-03-27
 */
namespace CodeTiburon\Exception;

class ControllerNotFoundException extends \Exception
{
    /**
     * @var string
     */
    protected $controller;

    /**
     * @param string $controller
     */
    public function __construct($controller)
    {
        parent::__construct('Controller Not Found "' . $controller . '"');
        $this->controller = $controller;
    }

    /**
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }
}