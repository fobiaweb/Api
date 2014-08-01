<?php
/**
 * ServerError class  - ServerError.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Fobia\Api\Exception;

/**
 * ServerError class
 *
 * @package   Fobia.Api.Exception
 */
class ServerError extends Error implements IApiException
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