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

    public function __construct($file, $params = null, $options = null)
    {
        $this->file = $file;
        parent::__construct($params, $options);
    }

    protected function configure()
    {
    }

    protected function execute()
    {
        $params = $this->getParams();
        $this->response = include $this->file;
    }
}
