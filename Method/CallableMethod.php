<?php
/**
 * This file is part of API.
 *
 * CallableMethod.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Fobia\Api\Method;

use Fobia\Api\Method\Method;

/**
 * CallableMethod class
 *
 * @package   Fobia.Api.Method
 */
class CallableMethod extends Method
{
    protected $callable;

    protected function configure()
    {
        $options = $this->getOptions();
        $this->file = $options['callable'];

        $this->setName($options['name']);
    }

    protected function execute()
    {
        $p   = $this->getDefinitionParams();
        $app = \App::instance();
        $db  = $app->db;

        $args = array($p);
        $this->response = call_user_func_array($callable, $args);
    }
}
