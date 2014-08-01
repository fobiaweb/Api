<?php
/**
 * This file is part of API.
 *
 * File.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Fobia\Api\Method;

use Fobia\Api\Method\Method;

/**
 * Метод 'File'
 * --------------------------------------------
 *
 * PARAMS
 * --------------------------------------------
 *
 *  offset      отступ, необходимый для получения определенного подмножества.
 *  count       количество записей, которые необходимо вернуть.
 *
 *
 * RESULT
 * --------------------------------------------
 * Возвращает результат успеха
 *
 *
 * @api        File
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

        include $this->file;

        $this->response = 1;
    }
}
