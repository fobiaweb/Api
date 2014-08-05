<?php
/**
 * Error class  - Error.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Fobia\Api\Exception;

/**
 * Exception_Error class
 *
 * @package   Fobia.Api.Exception
 */
class Error extends \Exception implements IApiException
{
    public $errorOriginal;
}

