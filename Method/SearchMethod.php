<?php
/**
 * SearchMethod class  - SearchMethod.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Fobia\Api\Method;

use Fobia\Api\Method\Method;

/**
 * SearchMethod class
 *
 * @package   Fobia.Api.Method
 */
abstract class SearchMethod extends Method
{
    /**
     * @var \Fobia\DataBase\Query\QuerySelect
     */
    protected $query;

    protected function configure()
    {
        $this->query = \App::instance()->db->createSelectQuery();

        $this->setDefinition(array(
            'name' => 'limit',
            'default' => 10,
        ));
        $this->setDefinition(array(
            'name' => 'offset',
            'default' => 0,
        ));
        $this->setDefinition(array(
            'name' => 'fields',
            'default' => array('id'),
            'parse' => 'parseFields',
            'assert' => array()
        ));
        $this->setDefinition(array(
            'name' => 'sort',
            'default' => 'id',
        ));
        $this->setDefinition(array(
            'name' => 'desc',
        ));
    }

    protected function execQuery()
    {
        $p = $this->getDefinitionParams();
        $this->query->limit($p['limit'], $p['offset']);

        if ($p['sort']) {
            $desc = ($p['desc']) ? 'DESC' : 'ASC';
            $sort = $this->getDefinition('sort');
            if ($p['sort'] == $sort['default']) {
                $desc = 'DESC';
            }
            $this->query->orderBy($p['sort'], $desc);
        }
    }

    protected function parseName($name)
    {
        $name = strtolower($name);

        $eng = array("yo", "ts", "ch", "sh", "shch", "yu", "ya", "h");
        $rus = array("е", "ц", "ч", "ш", "щ", "ю", "я", "х");
        $name = str_replace($eng, $rus, $name);

        $eng = array("a", "b", "v", "g", "d", "e", "g", "z", "i", "y", "k", "l", "m", "n", "o", "p", "r", "s", "t", "u", "f", "i", "e");
        $rus = array("а", "б", "в", "г", "д", "е", "ж", "з", "и", "й", "к", "л", "м", "н", "о", "п", "р", "с", "т", "у", "ф", "ы", "э");
        $name = str_replace($eng, $rus, $name);

        return $name;
    }

}