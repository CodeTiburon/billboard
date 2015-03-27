<?php
/**
 * Company: CodeTiburon
 * Date: 2014-03-27
 *
 * Singleton with arguments trait
 */
namespace CodeTiburon\Common;

trait SingletonTrait
{
    protected static $instance;

    /**
     * @param null|array $args
     *
     * @return static
     */
    public static function getInstance(...$args)
    {
        return static::$instance === null
            ? (static::$instance = new static(...$args))
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