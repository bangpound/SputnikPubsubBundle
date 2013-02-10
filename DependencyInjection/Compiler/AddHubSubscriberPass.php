<?php

namespace Sputnik\PubsubBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @package SputnikPubsubBundle_DependencyInjection
 * @subpackage Compiler
 */
class AddHubSubscriberPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('sputnik_pubsub.hub.provider')) {
            return;
        }

        $hubs = array();
        foreach ($container->findTaggedServiceIds('sputnik_pubsub.hub') as $id => $args) {
            $hubs[] = new Reference($id);
        }

        $container->getDefinition('sputnik_pubsub.hub.provider')->replaceArgument(0, $hubs);
    }
}
