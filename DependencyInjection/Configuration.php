<?php

namespace Sputnik\PubsubBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @package SputnikPubsubBundle_DependencyInjection
 */
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
                ->booleanNode('test_hub')->defaultFalse()->end()
                ->scalarNode('route')->defaultValue('sputnik_pubsub_callback_process')->end()
                ->scalarNode('hub_test_route')->defaultValue('sputnik_pubsub_hub_process')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
