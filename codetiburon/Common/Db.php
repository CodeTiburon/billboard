<?php
/**
 * Company: CodeTiburon
 * Date: 2014-03-27
 *
 * The Singleton Database class which extends PDO
 */
namespace CodeTiburon\Common;

class Db extends \PDO
{
    use SingletonTrait;

    public function __construct($host, $db, $user, $pass)
    {
        parent::__construct("mysql:host=$host;dbname=$db", $user, $pass, [
            self::ATTR_DEFAULT_FETCH_MODE => self::FETCH_ASSOC,
            self::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
        ]);
    }

    public function quoteValue($value)
    {
        return $this->quote($value, self::PARAM_STR);
    }

    public function quoteIdentifier($value)
    {
        return '`' . $value . '`';
    }
}