<?php

namespace Sputnik\Bundle\PubsubBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;

/**
 * List registered hubs.
 *
 * @package SputnikPubsubBundle_Command
 */
class ListHubsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('sputnik:pubsub:list-hubs')->setDescription('List registered hubs.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $provider = $this->getContainer()->get('sputnik_pubsub.hub.provider');
        foreach ($provider->getHubs() as $hub) {
            $output->writeln(sprintf('%s - %s', $hub->getName(), $hub->getUrl()));
        }
    }
}
