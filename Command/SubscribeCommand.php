<?php

namespace Sputnik\Bundle\PubsubBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

/**
 * Subscribe or unsubscribe.
 */
class SubscribeCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('sputnik:pubsub:subscribe')
            ->setDescription('Subscribe to topic URL.')
            ->addArgument('topic', InputArgument::REQUIRED, 'Topic URL.')
            ->addArgument('hub', InputArgument::REQUIRED, 'Hub name.')
            ->addOption('unsubscribe', 'u', InputOption::VALUE_NONE, 'Unsubscribe from topic.')
            ->addOption('context-host', null, InputOption::VALUE_NONE)
            ->addOption('context-scheme', null, InputOption::VALUE_NONE)
            ->addOption('context-base-url', null, InputOption::VALUE_NONE)
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $subscriber = $this->getContainer()->get('sputnik_pubsub.hub_subscriber');

        $context = $this->getContainer()->get('router')->getContext();
        if ($host = $input->getOption('context-host')) {
            $context->setHost($host);
        }
        if ($scheme = $input->getOption('context-scheme')) {
            $context->setScheme($scheme);
        }
        if ($baseUrl = $input->getOption('context-base-url')) {
            $context->setBaseUrl($baseUrl);
        } elseif ($this->getContainer()->getParameter('kernel.debug')) {
            $context->setBaseUrl('/app_dev.php');
        }

        if ($input->getOption('unsubscribe')) {
            $topic = $subscriber->unsubscribe($input->getArgument('topic'), $input->getArgument('hub'));
            if ($topic === false) {
                $output->writeln('<error>Unsubscribe failed</error>');
            } else {
                $output->writeln(sprintf('<info>Subscription removed: %s</info>', $topic->getId()));
            }
        } else {
            $topic = $subscriber->subscribe($input->getArgument('topic'), $input->getArgument('hub'));
            if ($topic === false) {
                $output->writeln('<error>Subscription failed</error>');
            } else {
                $output->writeln(sprintf('<info>Subscription created: %s</info>', $topic->getId()));
            }
        }
    }
}
