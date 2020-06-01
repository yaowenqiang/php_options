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
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Component\Finder\Finder;



require "vendor/autoload.php";


$whoops = new \Whoops\Run;

$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());
$whoops->register();

$a = new B();

$finder = new Finder();
$finder->ignoreVCS(true);
$finder->ignoreUnreadableDirs()->in(__DIR__);
$finder->date('since yesterday');
foreach ($finder as $item) {
    dump($item);
}
$finder->depth('== 0');
$filter = function (\SplFileInfo $file) {
    if (strlen($file) > 10) {
        return false;
    }
};
$finder->files()->filter($filter);
$finder->sortByAccessedTime();
$finder->sort(function (\SplFileInfo $a, SplFileInfo $b) {
    return strcmp($a->getRealPath(), $b->getRealPath());
});


$stopWatch = new Stopwatch();
$stopWatch->start('myevent');
sleep(1);
$event = $stopWatch->stop('myevent');
$event->getPeriods();
$event->getDuration();

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




