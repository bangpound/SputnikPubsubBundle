<?php

namespace Sputnik\Bundle\PubsubBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Sputnik\Bundle\PubsubBundle\DependencyInjection\Compiler\AddHubSubscriberPass;

class SputnikPubsubBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new AddHubSubscriberPass());
    }
}
