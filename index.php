<?php
/**
 *
 * Created by PhpStorm.
 * User: jacky.yao
 * Date: 2020/5/14
 * Time: 19:37
 */
use Monolog\Logger;
use Monolog\Processor\WebProcessor;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LogstashFormatter;
use Monolog\Formatter\LineFormatter;
use Pimple\Container;

require "vendor/autoload.php";

$container = new Container();
$container['logger'] = function ($c) {
    $dateFormat = 'Y-m-d$H:i:s.u';
//$output = "%datetime% > %level_name% > %message% %context% %extra%\n";
    $output = '[entry][ts]%datetime%[/ts][msg]%message%[/msg][context][/context][level_name]%level_name%[/level_name][lv]%level_name%[/lv][channel]%channel%[/channel][extra]%extra%[/extra][/entry]' . PHP_EOL;
    $formatter = new LineFormatter($output, $dateFormat);


    $logger = new Logger("zhibo");
    $logfileName = 'prod-' . date('Y-m-d') . '.log';
    $stream = new StreamHandler($logfileName);
//$stream->setFormatter(new LogstashFormatter('mylog'));
    $stream->setFormatter($formatter);

    $logger->pushHandler($stream,Logger::WARNING);
    return $logger;
};

$dotEnv = Dotenv\Dotenv::createImmutable(__DIR__, '.env');
$dotEnv->load();
$env = getenv('ENV');
dump($env);

//$logger->pushProcessor(function ($data){
//    if($data && is_array($data)){
//        foreach ($data as $k => $val){
//            $val = $val?$val:'';
//            if($k == 'datetime'){
////                $val = $this->udate('Y-m-d$H:i:s.u');
//                $val = $val->date;
//            }
//            $data[$k] = "[" . $k ."]" . ((is_array($val))?json_encode($val):$val) ."[/" . $k ."]";
//        }
//        $newStr = '[entry]' .implode('',$data). '[/entry]';
//        return [$newStr];
//    } else {
//        return $data;
//    }
//});

//$logger->pushProcessor(function ($record) {
//    $record['extra']['dommy'] = time();
//
//    return $record;
//});
//$logger->pushProcessor(["Monolog\\Processor\\WebProcessor", ""]);
$logger = $container['logger'];
$logger->info("hello");
$logger->warn("world");

