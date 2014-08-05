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

    /**
     * @var \CCallback
     */
    protected $callable;

    public function __construct($callable, $params = null, $options = null)
    {
        $this->callable = new \CCallback($callable);

        parent::__construct($params, $options);
    }

    protected function configure()
    {

    }

    protected function execute()
    {
        $this->response = $this->callable->invoke($this->getParams());
    }
}