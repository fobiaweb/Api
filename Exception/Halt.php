<?php
/**
 * Halt class  - Halt.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Fobia\Api\Exception;

/**
 * Halt class
 *
 * Исключение характерезуещее немедленый выход из обработки метода,
 * и формирует результат успешного выполнения
 *
 * @package   Fobia.Api.Exception
 */
class Halt extends \Exception 
{
}