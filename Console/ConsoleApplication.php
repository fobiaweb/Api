<?php
/**
 * FobiaApplication class  - FobiaApplication.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Fobia\Api\Console;

use Symfony\Component\Console\Application;

use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * FobiaApplication class
 *
 * @package Fobia.Api.Console.FobiaApplication
 */
class ConsoleApplication extends Application
{

    protected function getDefaultInputDefinition()
    {
        return new InputDefinition(array(
            new InputArgument('command', InputArgument::REQUIRED, 'The command to execute'),

            new InputOption('--quiet',          '-q', InputOption::VALUE_NONE, 'тихий (нет вывода).'),
            new InputOption('--verbose',        '-v|vv|vvv', InputOption::VALUE_NONE, 'Информативность сообщений'),
            new InputOption('--version',        '-V', InputOption::VALUE_NONE, 'Заценить версию приложения.'),
            new InputOption('--profile',        null, InputOption::VALUE_NONE, 'Показать временя и используемую память'),
        ));
    }
}