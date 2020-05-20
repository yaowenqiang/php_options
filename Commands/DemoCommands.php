<?php
/**
 *
 * Created by PhpStorm.
 * User: jacky.yao
 * Date: 2020/5/20
 * Time: 20:07
 */

use Commands\DemoCommand;
use Symfony\Component\Console\Application;


require __DIR__ . '/../vendor/autoload.php';
$application = new Application();
$application->add(new DemoCommand());
$application->run();

