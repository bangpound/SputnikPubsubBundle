<?php

namespace Sputnik\Bundle\PubsubBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class SputnikPubsubExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $configs);
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $loader->load('pubsub.xml');
        $loader->load('client.xml');

        if (!$config['live_hub']) {
            $container->getDefinition('sputnik_pubsub.hub_request')->replaceArgument(4, $config['hub_test_route']);
        }

        if ($config['driver'] === 'doctrine') {
            $loader->load('orm.xml');
        } elseif ($config['driver'] === 'doctrine_mongo') {
            $loader->load('odm.xml');
        }

        $container->setParameter('sputnik_pubsub.route', $config['route']);

        if ($container->getParameter('kernel.debug')) {
            $definition = $container->findDefinition('sputnik_pubsub.hub_subscriber');
            $arguments = $definition->getArguments();
            $arguments[3] = new Reference('debug.event_dispatcher');
            $definition->setArguments($arguments);

            $definition = $container->findDefinition('sputnik_pubsub.notification_handler');
            $arguments = $definition->getArguments();
            $arguments[0] = new Reference('debug.event_dispatcher');
            $definition->setArguments($arguments);
        }
    }
}
