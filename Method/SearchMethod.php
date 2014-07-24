<?php
/**
 * SearchMethod class  - SearchMethod.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Api\Method;

use Api\Method\Method;

/**
 * SearchMethod class
 *
 * @package   Api.Method
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
            'mode' => Method::VALUE_OPTIONAL,
            'default' => 10,
        ));
        $this->setDefinition(array(
            'name' => 'offset',
            'mode' => Method::VALUE_OPTIONAL,
            'default' => 0,
        ));
        $this->setDefinition(array(
            'name' => 'fields',
            'mode' => Method::VALUE_OPTIONAL,
            'default' => array(),
            'parse' => 'parseFields'
        ));
        $this->setDefinition(array(
            'name' => 'sort',
            'mode' => Method::VALUE_OPTIONAL,
        ));
        $this->setDefinition(array(
            'name' => 'desc',
            'mode' => Method::VALUE_OPTIONAL,
        ));
    }

    protected function execQuery()
    {
        $p = $this->getDefinitionParams();
        $this->query->limit($p['limit'], $p['offset']);

        if ($p['sort']) {
            $desc = ($p['desc']) ? 'DESC' : 'ASC';
            $this->query->orderBy($p['sort'], $desc);
        }
    }

    protected function parseName($name)
    {
        $eng = array("yo", "ts", "ch", "sh", "shch", "yu", "ya", "h");
        $rus = array("е", "ц", "ч", "ш", "щ", "ю", "я", "х");
        $name = str_replace($eng, $rus, $name);

        $eng = array("a", "b", "v", "g", "d", "e", "g", "z", "i", "y", "k", "l", "m", "n", "o", "p", "r", "s", "t", "u", "f", "i", "e");
        $rus = array("а", "б", "в", "г", "д", "е", "ж", "з", "и", "й", "к", "л", "м", "н", "о", "п", "р", "с", "т", "у", "ф", "ы", "э");
        $name = str_replace($eng, $rus, $name);

        return $name;
    }

}