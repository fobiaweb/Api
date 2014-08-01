<?php
/**
 * DeleteMethod class  - DeleteMethod.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Fobia\Api\Method;

use Fobia\Api\Method\Method;

/**
 * DeleteMethod class
 *
 * @package   Fobia.Api.Method
 */
abstract class DeleteMethod extends Method
{
    protected $tableName;
    protected $idName = 'id';

    protected function execDelete()
    {
        $p = $this->getDefinitionParams();

        $db = \ezcDbInstance::get();
        $q = $db->createDeleteQuery();
        $q->deleteFrom($this->tableName);
        $q->where($q->expr->eq($this->idName, $db->quote($p[$this->idName])));

        $stmt = $q->prepare();
        $stmt->execute();

        return $stmt->rowCount();
    }
}