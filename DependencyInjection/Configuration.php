<?php

namespace Sputnik\Bundle\PubsubBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('sputnik_pubsub');

        $rootNode
            ->children()
                ->booleanNode('live_hub')->defaultFalse()->end()
                ->scalarNode('route')->defaultValue('sputnik_pubsub_callback_process')->end()
                ->scalarNode('hub_test_route')->defaultValue('sputnik_pubsub_hub_process')->end()
                ->scalarNode('driver')
                    ->defaultValue('doctrine')
                    ->validate()
                        ->ifNotInArray(array('doctrine', 'doctrine_mongo'))
                        ->thenInvalid('Invalid driver: %s (allowed values: doctrine, doctrine_mongo)')
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
