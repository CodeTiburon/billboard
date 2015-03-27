<?php
/**
 * Company: CodeTiburon
 * Date: 2014-03-27
 *
 * Singleton trait
 */
namespace CodeTiburon\Common;

trait SingletonTrait
{
    protected static $instance;

    /**
     * @return $this
     */
    public static function getInstance()
    {
        return static::$instance === null
            ? (static::$instance = new static())
            :  static::$instance;
    }

    /**
     * Alias for SingletonTrait::getInstance()
     * @return $this
     */
    public static function i()
    {
        return self::getInstance();
    }

    /**
     * Protected constructor
     */
    protected function __contructor()
    {
    }
}