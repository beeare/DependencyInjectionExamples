<?php
use Symfony\Component\DependencyInjection\Reference;

$container->setParameter('mailer.transport', 'sendmail');
$container->register('mailer', '\beeare\DependencyInjection\Mailer')->addArgument('%mailer.transport%');

$container->register('newsletter_manager', '\beeare\DependencyInjection\NewsletterManager')
    ->addMethodCall('setMailer', array(new Reference('mailer')));
