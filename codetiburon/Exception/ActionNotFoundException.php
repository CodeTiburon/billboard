<?php
/**
 * Company: CodeTiburon
 * Date: 2014-03-27
 */
namespace CodeTiburon\Exception;

class ActionNotFoundException extends \Exception
{
    /**
     * @var string
     */
    protected $action;

    /**
     * @param string $view
     */
    public function __construct($view)
    {
        parent::__construct('Action Not Found "' . $view . '"');
        $this->view = $view;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }
}