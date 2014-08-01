<?php
/**
 * app.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

$_ENV['no_stderr'] = true;

require_once __DIR__ . '/vendor/autoload.php';


use Fobia\Api\Console\ConsoleApplication;

$application = new ConsoleApplication();
$application->add(new Fobia\Api\Console\Command\ApiCreateCommand());
$application->add(new Fobia\Api\Console\Command\ApiSearchCommand());
$application->run();