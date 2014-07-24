<?php
/**
 * ApiCreateCommand class  - ApiCreateCommand.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Api\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

/**
 * ApiCreateCommand class
 *
 * @package   Console\Command
 */
class ApiCreateCommand extends Command
{

    protected $dir = null;
    protected $template = null;

    protected function defaultConfigure()
    {
        $this->dir = 'app/Api';
        $this->template = dirname(__DIR__) . '/default.tpl';
    }

    protected function configure()
    {
        $this->defaultConfigure();
        
        // Arguments
        $this
                ->setName('api:create')
                ->setDescription('Создать метод API')
                ->addArgument('name', InputArgument::REQUIRED, 'Название метода')
                ;

        // Options
        $this
                ->addOption('output', 'o', InputOption::VALUE_NONE ,
                            'директоория для сохранения')
                ->addOption('template', null, InputOption::VALUE_OPTIONAL, 'Шаблон')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        //$html = $this->template($name);

        if ($template = $input->getOption('template')) {
            if (!file_exists($template)) {
                throw new \Exception('aaaaaaaaaaaaaaaaaa');
            }
        }
        


        $dir = $input->getOption('output');
        if ($dir) {
            $path = explode('.', $name);
            array_pop($path);
            $path = SYSPATH . '/app/Api/' . implode('/', $path);
            @mkdir($path, 0777, true);

            $file = $path . '/' . $name . '.php';
            if (file_exists($file)) {
                $output->writeln('<error>Файл существует<error>');
                exit();
            }
            if(!file_put_contents($file , $html)) {
                $output->writeln('<error>Не удалось записать файл<error>');
            }
        } else {
            print_r($html);
        }
    }


    protected function template($name)
    {
        // Первые символы после точки в верхний регистр
        $class = preg_replace_callback('/^\w|\.\w/', function ($matches) {
            return strtoupper($matches[0]);
        }, $name);
        // Префикс к классу и заменяем точки подчеркиванием
        $class   = 'Api_' . str_replace('.', '_', $class);

        // Загружаем шаблон
        $content = file_get_contents($this->dir . '/default.tpl');

        // Производим замену
        $content = preg_replace(array('/{{name}}/', '/{{classname}}/'),
                                array($name, $class), $content);

        return $content;
    }
}