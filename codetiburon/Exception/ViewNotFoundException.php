<?php
/**
 * Company: CodeTiburon
 * Date: 2014-03-27
 */
namespace CodeTiburon\Exception;

class ViewNotFoundException extends \Exception
{
    /**
     * @var string
     */
    protected $view;

    /**
     * @param string $view
     */
    public function __construct($view)
    {
        parent::__construct('View Not Found "' . $view . '"');
        $this->view = $view;
    }

    /**
     * @return string
     */
    public function getView()
    {
        return $this->view;
    }
}