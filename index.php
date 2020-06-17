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
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Mail\Mailer;

use Symfony\Component\HttpKernel\HttpKernel;


use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

use Symfony\Component\Translation\Loader\YamlFileLoader;

use Symfony\Component\Translation\Loader\PhpFileLoader;

use Symfony\Component\Form\Forms;

use Symfony\Component\HttpClient\HttpClient;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;


use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

use Symfony\Component\HttpFoundation\Request;

use Composer\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;






require "vendor/autoload.php";
$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());
$whoops->register();

$request = Request::createFromGlobals();
$dispatcher = new EventDispatcher();
$controllerResolver = new ControllerResolver();
$argumentResolver = new ArgumentResolver();
$kernel = new HttpKernel($dispatcher, $controllerResolver, new RequestStack(), $argumentResolver);
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);



class A {
    public static function who()
    {
       echo __CLASS__;
       dump(new self());
    }

    public static function test()
    {
        self::who();
    }

    public static function test2()
    {
        static::who();
    }
}

class B extends A{
    public static function who()
    {
        echo __CLASS__;
   }
}

B::test();
B::test2();

$request = Request::CreateFromGlobals();

$request->query->get('foo');

$request->query->get('foo', 'bar');

//?foo[bar]=baz
$request->query->get('foo');
$request->query->get('foo[bar]');
$request->query->get('foo')['bar'];



$encoder = [new XmlEncoder(),new JsonEncode()];
$normalizer = [new ObjectNormalizer()];
$seriqalizer = new Serializer($normalizer, $encoder);




$expressionLanguage = new ExpressionLanguage();
dump($expressionLanguage->evaluate("1 + 2"));
dump($expressionLanguage->compile("1 + 2"));

$client = HttpClient::create();

$response = $client->request('GET','api.github.com/repos/symfony/symfony-docs');
$statusCode = $response->getStatusCode();
$contentType = $response->getHeaders()['content-type'][0];
$content = $response->getContent();
$content = $response->toArray();

$formFactory = Forms::createFormFactory();

$containerBuilder = new ContainerBuilder();
$containerBuilder
    ->register('mailer', "Mailer")
    ->addArgument('sendmail');

$containerBuilder
    ->setParameter('mail.transport', 'sendmail');

$containerBuilder
    ->register('mailer', 'Mail')
    ->addArgument('%mail.transport%');

$containerBuilder->register('newsletter_manager', "NewsletterManager")
    ->addArgument(new Reference('mailer'))
    ->addMethodCall('setMailer', [new Reference('mailer')]);
;


$newsletterManager = $containerBuilder->get('newsletter_manager');



$loader = new XmlFileLoader($containerBuilder, new FileLocator(__DIR__));
$loader->load('services.xml');

$loader = new YamlFileLoader($containerBuilder, new FileLocator(__DIR__));
$loader->load('services.yaml');
//$loader->load('services.config', 'xml');

$loader = new PhpFileLoader($containerBuilder, new FileLocator(__DIR__));
$loader->load('services.php');


$a = new B();

$finder = new Finder();
$finder->ignoreVCS(true);
$finder->ignoreUnreadableDirs()->in(__DIR__);
$finder->date('since yesterday');
foreach ($finder as $item) {
    dump($item);
}
$finder->depth('== 0');
$filter = function(SplFileInfo $file) {
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




