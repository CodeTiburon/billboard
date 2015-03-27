<?php
/**
 * Company: CodeTiburon
 * Date: 2014-03-27
 */
namespace CodeTiburon\Exception;

class RouteNotFoundException extends \Exception
{
    /**
     * @var string
     */
    protected $route;

    /**
     * @param string $route
     */
    public function __construct($route)
    {
        parent::__construct('Route Not Found "' . $route . '"');
        $this->route = $route;
    }

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }
}