<?php
namespace beeare\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

class DependencyInjectionTest extends \PHPUnit_Framework_TestCase
{
    public function testRegisterMailer()
    {
        $container = new ContainerBuilder();

        $container->setParameter('mailer.transport', 'sendmail');
        $container->register('mailer', '\beeare\DependencyInjection\Mailer')
            ->addArgument('%mailer.transport%');

        /** @var \beeare\DependencyInjection\Mailer $mailer */
        $mailer = $container->get('mailer');
        $this->assertInstanceOf('\beeare\DependencyInjection\Mailer', $mailer);
        $this->assertEquals('sendmail', $mailer->getTransport());
    }

    public function testInjectMailerToNewsletterManagerViaConstructor()
    {
        $container = new ContainerBuilder();

        $container->setParameter('mailer.transport', 'sendmail');
        $container->register('mailer', '\beeare\DependencyInjection\Mailer')
            ->addArgument('%mailer.transport%');
        $container->register('newsletter_manager', '\beeare\DependencyInjection\NewsletterManager')
            ->addArgument(new Reference('mailer'));

        /** @var \beeare\DependencyInjection\NewsletterManager $newsletterManager */
        $newsletterManager = $container->get('newsletter_manager');
        $this->assertInstanceOf('\beeare\DependencyInjection\NewsletterManager', $newsletterManager);
        $this->assertInstanceOf('\beeare\DependencyInjection\Mailer', $newsletterManager->getMailer());
    }

    public function testInjectMailerToNewsletterManagerViaSetter()
    {
        $container = new ContainerBuilder();

        $container->setParameter('mailer.transport', 'sendmail');
        $container->register('mailer', '\beeare\DependencyInjection\Mailer')
            ->addArgument('%mailer.transport%');
        $container->register('newsletter_manager', '\beeare\DependencyInjection\NewsletterManager')
            ->addMethodCall('setMailer', array(new Reference('mailer')));

        /** @var \beeare\DependencyInjection\NewsletterManager $newsletterManager */
        $newsletterManager = $container->get('newsletter_manager');
        $this->assertInstanceOf('\beeare\DependencyInjection\NewsletterManager', $newsletterManager);
        $this->assertInstanceOf('\beeare\DependencyInjection\Mailer', $newsletterManager->getMailer());
    }

    public function testContainerSetupFromConfigXml()
    {
        $container = new ContainerBuilder();
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__));
        $loader->load('../services.xml');

        /** @var \beeare\DependencyInjection\Mailer $mailer */
        $mailer = $container->get('mailer');
        $this->assertInstanceOf('\beeare\DependencyInjection\Mailer', $mailer);
        $this->assertEquals('sendmail', $mailer->getTransport());

        /** @var \beeare\DependencyInjection\NewsletterManager $newsletterManager */
        $newsletterManager = $container->get('newsletter_manager');
        $this->assertInstanceOf('\beeare\DependencyInjection\NewsletterManager', $newsletterManager);
        $this->assertInstanceOf('\beeare\DependencyInjection\Mailer', $newsletterManager->getMailer());
    }

    public function testContainerSetupFromConfigYml()
    {
        $container = new ContainerBuilder();
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__));
        $loader->load('../services.yml');

        /** @var \beeare\DependencyInjection\Mailer $mailer */
        $mailer = $container->get('mailer');
        $this->assertInstanceOf('\beeare\DependencyInjection\Mailer', $mailer);
        $this->assertEquals('sendmail', $mailer->getTransport());

        /** @var \beeare\DependencyInjection\NewsletterManager $newsletterManager */
        $newsletterManager = $container->get('newsletter_manager');
        $this->assertInstanceOf('\beeare\DependencyInjection\NewsletterManager', $newsletterManager);
        $this->assertInstanceOf('\beeare\DependencyInjection\Mailer', $newsletterManager->getMailer());
    }

    public function testContainerSetupFromConfigPhp()
    {
        $container = new ContainerBuilder();
        $loader = new PhpFileLoader($container, new FileLocator(__DIR__));
        $loader->load('../services.php');

        /** @var \beeare\DependencyInjection\Mailer $mailer */
        $mailer = $container->get('mailer');
        $this->assertInstanceOf('\beeare\DependencyInjection\Mailer', $mailer);
        $this->assertEquals('sendmail', $mailer->getTransport());

        /** @var \beeare\DependencyInjection\NewsletterManager $newsletterManager */
        $newsletterManager = $container->get('newsletter_manager');
        $this->assertInstanceOf('\beeare\DependencyInjection\NewsletterManager', $newsletterManager);
        $this->assertInstanceOf('\beeare\DependencyInjection\Mailer', $newsletterManager->getMailer());
    }
}
