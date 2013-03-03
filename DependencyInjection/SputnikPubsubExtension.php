<?php

namespace Sputnik\Bundle\PubsubBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * @package SputnikPubsubBundle_DependencyInjection
 */
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

        if ($config['test_hub']) {
            $container->getDefinition('sputnik_pubsub.hub.request')->replaceArgument(4, $config['hub_test_route']);
        }

        if ($config['driver'] === 'doctrine') {
            $loader->load('orm.xml');
        } elseif ($config['driver'] === 'doctrine_mongo') {
            $loader->load('odm.xml');
        } elseif ($config['driver'] === 'mandango') {
            $loader->load('mandango.xml');
        }

        $container->setParameter('sputnik_pubsub.route', $config['route']);
    }
}
