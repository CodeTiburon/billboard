<?php
/**
 * Company: CodeTiburon
 * Date: 2014-03-27
 */
namespace CodeTiburon\DataMapper;

interface MapperInterface
{
    public function fetchOne($where);

    public function fetchAll($where);

    public function save(&$data);

    public function insert($data);

    public function update($data, $where);

    public function delete($where);
}