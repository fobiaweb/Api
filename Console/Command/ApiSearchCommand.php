<?php
/**
 * ApiSearchCommand class  - ApiSearchCommand.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Fobia\Api\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

/**
 * ApiSearchCommand class
 *
 * @package   Fobia.Api.Console.Command
 */
class ApiSearchCommand extends Command
{

    protected $dir = 'app/Api';

    protected function configure()
    {
        $this
                ->setName('api:search')
                ->setDescription('Поиск методов API')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $arr = \ezcBaseFile::findRecursive(
                        SYSPATH . "/app/Api", array('@.*.php$@')
        );

        $apiHandler = new \Api\ApiHandler();
        $result = array();
        foreach ($arr as $file) {
            $method = basename($file, ".php");
            $class  = $apiHandler->getClass($method);

            $rc  = new \ReflectionClass($class);
            $txt = $rc->getDocComment();
            preg_match("|/\*\*\s*( \* [^-][^\n]+\n)+|i", $txt, $m);
            $txt = preg_replace(array("|/\*\*\n \* |", "#\n ?\* #"),
                                array("", " "), $m[0]);
            $txt = trim($txt);

            $result[$method] = array($class, $txt);
        }

        $output->writeln("<comment>Список методов:</comment>");
        foreach ($result as $k => $v) {
            list($base) = explode(".", $k);
            if ($_base != $base) {
                $output->writeln("<comment>$base</comment>");
            }
            $_base = $base;

            $output->writeln(sprintf("  <info>%-24s</info> %s", $k, $v[1]));
        }
    }
}
