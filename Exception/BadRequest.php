<?php
/**
 * BadRequest class  - BadRequest.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Api\Exception;

/**
 * BadRequest class
 *
 * Исключение возникает в случае неверно переданых параметров,
 * и генерирует ошибку в Api методе
 *
 * @package   Api.Exception
 */
class BadRequest extends Error
{
    /**
     * 
     * @param string $params
     * @param ...
     */
    public function __construct($params)
    {
        $params = func_get_args();
        $message = "Не передан либо имеет не правельный формат один из аргументов ('" . implode("', '", $params) . "')";
        parent::__construct($message, 5);
    }

}