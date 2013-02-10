<?php

namespace Sputnik\PubsubBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @package SputnikPubsubBundle_DependencyInjection
 * @subpackage Compiler
 */
class AddNotificationListenerPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('sputnik_pubsub.event_dispatcher')) {
            return;
        }

        $dispatcher = $container->getDefinition('sputnik_pubsub.event_dispatcher');

        foreach ($container->findTaggedServiceIds('sputnik_pubsub.event_subscriber') as $id => $args) {
            $dispatcher->addMethodCall('addSubscriber', array(new Reference($id)));
        }
    }
}
