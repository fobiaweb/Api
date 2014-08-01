<?php
/**
 * This file is part of API.
 *
 * FileMethod.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Fobia\Api\Method;

use Fobia\Api\Method\Method;

/**
 * FileMethod class
 *
 * @package   Fobia.Api.Method
 */
class FileMethod extends Method
{
    protected $file;

    protected function configure()
    {
        $options = $this->getOptions();
        $this->file = $options['file'];

        $this->setName($options['name']);
    }

    protected function execute()
    {
        $p   = $this->getDefinitionParams();
        $app = \App::instance();
        $db  = $app->db;

        $this->response = include $this->file;
    }
}
