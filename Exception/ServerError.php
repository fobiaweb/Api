<?php
/**
 * ServerError class  - ServerError.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Api\Exception;

/**
 * ServerError class
 *
 * @package   Api.Exception
 */
class ServerError extends Error
{
    public function __construct($message = null)
    {
        $msg = 'Произошла ошибка сервера.';
        if ($message !== null) {
            $msg .= ' - ' . $message . '.';
        }
        parent::__construct($msg);
    }

}