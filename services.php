<?php
/**
 *
 * Created by PhpStorm.
 * User: jacky.yao
 * Date: 2020/6/2
 * Time: 22:07
 */
use Symfony\Component\DependencyInjection\Loader\Configurator;
return function (Configurator\ContainerConfigurator $configurator) {
    $configurator->parameters()
        ->set('mailer.transport', 'sendmail')
    ;
    $services = $configurator->services();
    $services->set('mailer', "Mail")
        ->arg(['%mailer.transport%'])
    ;
    $services->set('newsletter_manager', "NewsletterManager")
        ->call('setMailer', [$services('mailer')])
    ;

};