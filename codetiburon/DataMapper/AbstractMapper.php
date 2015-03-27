<?php
/**
 * Company: CodeTiburon
 * Date: 2014-03-27
 */
namespace CodeTiburon\DataMapper;

use CodeTiburon\Common\Db;

class AbstractMapper implements MapperInterface
{
    protected $table = null;

    /**
     * @param array $where
     * @return array
     */
    public function fetchOne($where)
    {
        $stmt = Db::i()->query("SELECT * FROM {$this->table} {$this->_whereToSql($where)} LIMIT 1");
        return $stmt->fetch();
    }

    /**
     * @param array $where
     * @return array
     */
    public function fetchAll($where)
    {
        $stmt = Db::i()->query("SELECT * FROM {$this->table} {$this->_whereToSql($where)}");
        return $stmt->fetchAll();
    }

    /**
     * @param $data
     */
    public function save(&$data)
    {
        if (empty($data['id'])) {
            $data['id'] = $this->insert($data);
        } else {
            $this->update($data, ['id' => $data['id']]);
        }
    }

    /**
     * @param $data
     * @return string
     */
    public function insert($data)
    {
        if (isset($data['id'])) {
            unset($data['id']);
        }

        $sql  = "INSERT INTO {$this->table} (";
        $sql .= implode( ',', array_map([Db::i(), 'quoteIdentifier'], array_keys($data)) );
        $sql .= ') VALUES (';
        $sql .= implode( ',', array_map([Db::i(), 'quoteValue'], array_values($data)) );

        Db::i()->query($sql);
        return Db::i()->lastInsertId();
    }

    /**
     * @param $data
     * @param $where
     * @return int
     */
    public function update($data, $where)
    {
        $sql  = "UPDATE {$this->table} SET ";

        foreach ($data as $key => $value) {
            $sql .= Db::i()->quoteIdentifier($key) . '=' . Db::i()->quoteValue($value) . ',';
        }

        $stmt = Db::i()->query(substr($sql, 0, -1));
        return $stmt->rowCount();
    }

    /**
     * @param $where
     * @return int
     */
    public function delete($where)
    {
        $stmt = Db::i()->query("DELETE FROM {$this->table} {$this->_whereToSql($where)} LIMIT 1");
        return $stmt->rowCount();
    }

    /**
     * @param array|string $where
     * @return string
     */
    protected function whereToSql($where)
    {
        if (empty($where)) {
            return '';
        }

        if (is_array($where)) {
            $where = 'WHERE ' . $this->_whereToSql($where);
        }

        return $where;
    }

    /**
     * Simplest way to parse array where condition in the format:
     * [
     *   'a' => '1',
     *
     *   'OR' => ['b' => '2', 'c' => ['3', '33'] ]
     *
     *   'AND' => [
     *     'd' => '4',
     *     'e' => '5',
     *
     *     'OR' => [
     *       'f' => '6',
     *       'g' => '7'
     *      ]
     *   ]
     *
     *   'h' => '8'
     * ]
     * Will be converted to:
     *
     * `a`='1' AND (`b`='2' OR `c` IN ('3','33')) AND (`d`='4' AND `e`='5' AND (`f`='6' OR `g`='7')) AND `h`='8'
     *
     * @param string $operator (OR | AND)
     * @param array $data
     * @return string
     */
    protected function _whereToSql($data, $operator = 'AND')
    {
        $result = '';

        foreach ($data as $key => $value) {
            if ($result !== '') {
                $result .= ' ' . $operator . ' ';
            }

            if (is_array($value) && in_array(strtolower($key), ['OR', 'AND'])) {
                $result .= '(' . $this->_whereToSql($value, $key) . ')';
            } else {
                $result .= Db::i()->quoteIdentifier($key);

                if (is_array($value)) {
                    $result .= ' IN (';
                    $result .= implode(',', array_map([Db::i(), 'quoteValue'], $value));
                    $result .= ')';
                }

                $result .= '=';
                $result .= Db::i()->quoteValue($value);
            }
        }

        return $result;
    }
}