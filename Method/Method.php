<?php
/**
 * Method class  - Method.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Fobia\Api\Method;

use Fobia\Debug\Log;

/**
 * Method class
 *
 * @package   Fobia.Api.Method
 */
abstract class Method
{

    const VALUE_NONE     = 0;
    /**
     * Обязательно
     */
    const VALUE_REQUIRED = 1;
    /**
     * Опционально
     */
    const VALUE_ARRAY    = 2;


    private $params;
    private $options;
    private $definition;
    private $definitionMergedWithArgs;
    private $ignoreValidationErrors = false;
    private $name;

    /**
     *
     * @var \Fobia\Api\Exception\Error
     */
    public $exc;

    /**
     * @var mixed результат
     */
    protected $response;

    /**
     * @internal
     */
    public function __construct($params = null, $options = null)
    {
        $this->params     = (array) $params;
        $this->definition = array();
        $this->options    = (array) $options;
        
        if ($this->options['name']) {
            $this->name = $this->options['name'];
        }

        $this->configure();
    }

    /**
     * @internal
     */
    public function __invoke()
    {
        $this->dispatchMethod('invoke', func_get_args());
        return $this->getFormatResponse();
    }

    /**
     * Выполнить метод
     *
     * @return boolean  флаг об успешности выполнения метода
     */
    public function invoke()
    {
        $this->exc      = null;
        $this->response = null;
        Log::info("[API]:: Вызов метода '$this->name' - ", $this->params);

        $r = null;
        try {
            $this->initialize();
            $execute = $this->getOptions('execute');
            if (!$execute) {
                $execute = 'execute';
            }
            $this->dispatchMethod($execute, func_get_args());
        } catch (\Fobia\Api\Exception\Halt $exc) {
            // Halt
        } catch (\Fobia\Api\Exception\Error $exc) {
            $this->exc = $exc;
        } catch (\Exception $exc) {
            Log::error("[API]:: Неизвестная ошибка (" . get_class($exc) . ") " . $exc->getMessage());
            $this->exc = new \Fobia\Api\Exception\Error("Неизвестная ошибка. (" . get_class($exc) . ")" );
            $this->exc->errorOriginal = $exc->getMessage();
        }

        $this->log();

        if ($this->exc) {
            Log::error("[API]:: (" . get_class($this->exc) . ") " . $this->exc->getMessage());
            if (!$this->ignoreValidationErrors) {
                throw $this->exc;
            }
            return false;
        }
        return true;
    }

    /**
     * Возвращает результат
     *
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Возвращает форматированный результат
     *
     * @return array
     */
    public function getFormatResponse()
    {
        if ($this->exc === null) {
            return array('response' => $this->response);
        } else {
            return array(
                'error' => $this->errorInfo()
            );
        }
    }

    /**
     * Информация об ошибки
     *
     * @return null|array
     */
    public function errorInfo()
    {
        if ($this->exc !== null) {
            return array(
                'err_msg'  => $this->exc->getMessage(),
                'err_code' => $this->exc->getCode(),
                'method'   => $this->getName(),
                'params'   => $this->params,
                'err_treace' => $this->exc->getTraceAsString()
            );
        } else {
            return null;
        }
    }

    /**
     * Ignores validation errors.
     *
     * This is mainly useful for the help command.
     *
     * @return void
     */
    public function ignoreValidationErrors()
    {
        $this->ignoreValidationErrors = true;
    }

    /**
     * Configures the current command.
     *
     * @return void
     */
    protected function configure()
    {

    }

    /**
     * Initializes the command just after the input has been validated.
     *
     * @return void
     */
    protected function initialize()
    {
        $params     = $this->params;
        $definition = $this->definition;
        $args = array();

        foreach ($definition as $key => $value) {
            if (array_key_exists($key, $params)) {
                $args[$key] = $params[$key];
            } else {
                if ($value['default'] !== null) {
                    $args[$key] = $value['default'];
                } else if ($value['mode'] == self::VALUE_REQUIRED) {
                    throw new \Fobia\Api\Exception\BadRequest($key);
                }
                continue;
            }

            foreach ((array) $value['parse'] as $cb) {
                $callback_args = array();
                if (is_array($cb)) {
                    $callback_args = $cb;
                    $cb = array_shift($callback_args);
                    array_unshift($callback_args, $args[$key]);
                } else {
                    $callback_args = array($args[$key]);
                }

                if (is_callable($cb)) {
                    $args[$key] = call_user_func_array($cb, $callback_args);
                } else {
                    throw new \Fobia\Api\Exception\ServerError('Не верный формат callable - ' . $cb);
                }
            }

            foreach ( (array) $value['assert'] as $cb) {
                if (is_callable($cb)) {
                    if( !call_user_func_array($cb, array($args[$key])) ) {
                        throw new \Fobia\Api\Exception\BadRequest($key);
                    }
                } else {
                    throw new \Fobia\Api\Exception\ServerError('Не верный формат callable - ' . $cb);
                }
            }
        }

        $this->definitionMergedWithArgs =  $args ;
    }

    /**
     * Устанавить атрибуты параметру
     *
     * <pre>
     * mode     - режим
     * default  - по умолчанию
     * parse    - парсировка и разборка
     * assert   - проверка валидности
     * </pre>
     *
     * @param string $name
     * @param array $options
     */
    protected function setDefinition($name, $options = array())
    {
        if (is_array($name)) {
            $options = $name;
            $name = $options['name'];
            unset($options['name']);
        } elseif ($options === null) {
            unset($this->definition[$name]);
            return;
        } else {
            if (!is_array($options)) {
                $options = array();
            }
        }

        $name = trim($name);
        if (!$name) {
            throw new \RuntimeException("Не указано имя параметра метода");
        }

        // Если параметр определяеться впервые
        if (!array_key_exists($name, $this->definition)) {
            $options_default = array(
                'mode'    => self::VALUE_NONE,
                'default' => null,
                'parse'   => null,  // array(),
                'assert'  => null,  // array(),
            );
            // Если поле обязательно и нет проверок, устанавливаем проверку на
            // кол. символов
            if ($options['mode'] == self::VALUE_REQUIRED
                    && !array_key_exists('assert', $options)) {
                $options['assert'] = 'strlen';
            }
            // Если нет преобразований - устанавливаем 'trim'
            if (!array_key_exists('parse', $options)) {
                $options['parse'] = 'trim';
            }
        } else { // Если параметр уже имееться, переопределяем опции
            $options_default = $this->definition[$name];
        }

        $this->definition[$name] = array_merge($options_default, $options);
    }

    /**
     * Устанавить атрибуты параметру
     * # array (name, mode, default, parse, assert)
     *
     * @param array $options
     */
    protected function setAddDefinition(array $options)
    {
        $options_set = array(
            'name'    => $options[0],
            'mode'    => @$options[1],
            'default' => @$options[2],
            'parse'   => @$options[3],
            'assert'  => @$options[4],
        );
        $this->setDefinition($options_set);
    }

    /**
     * Список определений параметров метода
     *
     * @return array
     */
    public function getDefinition($name = null)
    {
        if ($name === null) {
            return $this->definition[$name];
        }
        return $this->definition;
    }

    /**
     * Опции объекта
     *
     * @return mixed
     */
    protected function getOptions($name = null)
    {
        if ($name === null) {
            return $this->options;
        }
        return $this->options[$name];
    }

    /**
     * Обработаные параметры
     *
     * @return array
     */
    protected function getDefinitionParams()
    {
        return $this->definitionMergedWithArgs;
    }

    /**
     * Переданые параметры
     *
     * @return array
     */
    protected function getParams()
    {
        return $this->params;
    }

    protected function setName($name)
    {
        $this->name = $name;
    }

    protected function log()
    {
        $app = \Fobia\Base\Application::getInstance();
        $login = $app->auth->getUser()->getUsername();
        if (!$login) {
            $login = 'guest';
        }
        $str = "[".date(\CDateTime::ATOM)."]"
                . " - " .$app->request->getClientIp()
                // . " - " . $app->request->getPath() . "?" . $app->request->getQueryString()
                . " - " . $login
                . " - " . $this->getName()
                . " " . json_encode($this->getDefinitionParams())
                . PHP_EOL
                ;

        $file = LOGS_DIR . '/api.log';

        file_put_contents($file, $str, FILE_APPEND);
    }

    /**
     * Название метода
     *
     * @return string
     */
    public function getName()
    {
        if ($this->name) {
            return $this->name;
        }
        // Если метод не установлен по умолчанию, вычисляем из названия класса
        $class = preg_replace_callback('/^\w|_\w|\.\w/', function($matches) {
            return strtolower($matches[0]);
        }, str_replace('_', '.', get_class($this)));

        return preg_replace('/api\./', '', $class);
    }

    abstract protected function execute();

    /**
     * ХАК к вызову метода.
     * Аналог 'call_user_func_array', но работает быстрее
     *
     * @param string $method
     * @param array $p
     * @return mixed
     */
    protected function dispatchMethod($method, array $p = null)
    {
        $o = $this;
        switch (@count($p)) {
            case 0: return $o->{$method}();
            case 1: return $o->{$method}($p[0]);
            case 2: return $o->{$method}($p[0], $p[1]);
            case 3: return $o->{$method}($p[0], $p[1], $p[2]);
            case 4: return $o->{$method}($p[0], $p[1], $p[2], $p[3]);
            case 5: return $o->{$method}($p[0], $p[1], $p[2], $p[3], $p[4]);
            default: return call_user_func_array(array($o, $method), $p);
        }
    }
}