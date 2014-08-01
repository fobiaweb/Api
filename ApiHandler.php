<?php
/**
 * ApiHandler class  - ApiHandler.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Fobia\Api;

use Fobia\Debug\Log;

/**
 * ApiHandler class
 *
 * @package   Api
 */
class ApiHandler
{
    /**
     * @var string директория классов
     */
    protected $classDirectory;

    /**
     * 'apiMethodName' => array('className', 'classMethod')
     * @var array
     */
    protected $apimap = array();

    public function __construct($classDirectory = null)
    {
        $this->classDirectory = ($classDirectory) ? $classDirectory : SYSPATH . '/app/Api';
    }

    /**
     * Выполнить метод
     *
     * @param string $method
     * @param array $params
     * @return array
     */
    public function request($method, $params = null)
    {
        $params = (array) $params;

        // Ищем в определениях
        if (array_key_exists($method, $this->apimap)) {
            $map = $this->apimap[$method];
        } else {
            $class = $this->getClass($method);
            $classMethod = 'invoke';
            $map = array();
        }

        if ($type == 'file') {
            include_once $map[1];
        }

        if ( ! class_exists($class) || ! method_exists( $class,  $classMethod)) {
            return array(
                'error' => array(
                    'err_msg'  => 'неизвестный метод',
                    'err_code' => 0,
                    'method'   =>  $method,
                    'params'   =>  $params
                )
            );
        }

        $obj = new $class($params);
        $obj->ignoreValidationErrors();
        dispatchMethod($obj, $classMethod, $map);
        return $obj->getFormatResponse();
    }

    /**
     * Генерирует название класса и подключает при необходимости
     *
     * @param string $method
     * @return string
     */
    public function getClass($method, $autoload = true)
    {
        $class = 'Api_' . preg_replace_callback('/^\w|_\w/', function($matches) {
            return strtoupper($matches[0]);
        }, str_replace('.', '_', $method));

        if ( ! class_exists($class) && $autoload) {
            Log::warning("Class '$class' not autoloaded");
            $list = explode('.', $method);
            array_pop($list);
            array_push($list, $method);

            $file = $this->classDirectory . '/' . implode('/', $list) . '.php';
            if (file_exists($file)) {
                require_once $file;
            }

            if ( ! class_exists($class) ) {
                Log::error("Class '$class' not exists.");
            }
        }

        return $class;
    }

    protected function executeFile($file, $p)
    {
        if (!is_array($p)) {
            $p = array($p);
        }

        return include_once $map[1];
    }

    protected function executeObject($class, $p, $args = null)
    {
        list($class, $method) = explode(":", $class);
        if (!$method) {
            $method = "invoke";
        }

        $obj = new $class($p);
        return $class->$method();
    }

    protected function executeCallable($callable, $p, $args = null)
    {
        if (!is_array($args)) {
            $args = array();
        }
        array_unshift($args, $p);
        return call_user_func_array($callable, $args);
    }
}