<?php
/**
 * Company: CodeTiburon
 * Date: 2014-03-27
 */
namespace CodeTiburon\Exception;

class ServiceNotFoundException extends \Exception
{
    /**
     * @var string
     */
    protected $service;

    /**
     * @param string $service
     */
    public function __construct($service)
    {
        parent::__construct('Service Not Found "' . $service . '"');
        $this->service = $service;
    }

    /**
     * @return string
     */
    public function getService()
    {
        return $this->service;
    }
}