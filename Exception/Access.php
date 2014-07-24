<?php
/**
 * Access class  - Access.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Api\Exception;

/**
 * Access class
 *
 * @package   Api\Exception
 */
class Access extends Error
{
    public function __construct($access)
    {
        parent::__construct("Недостаточно прав ({$access})");
    }

}