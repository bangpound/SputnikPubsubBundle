<?php

namespace Sputnik\Bundle\PubsubBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Push test notification to topic.
 *
 * @package SputnikPubsubBundle_Command
 */
class PushCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('sputnik:pubsub:push')
            ->setDescription('Push pubsub notification from hub.')
            ->addArgument('topic', InputArgument::REQUIRED, 'Topic URL.')
            ->addArgument('hub', InputArgument::REQUIRED, 'Hub name.')
            ->addArgument('file', InputArgument::REQUIRED, 'Path to file with contents to send.')
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
        $manager = $this->getContainer()->get('sputnik_pubsub.manager.topic');
        $topic = $manager->findOneBy(array('topicUrl' => $input->getArgument('topic'), 'hubName' => $input->getArgument('hub')));
        if ($topic === null) {
            return $output->writeln('<error>Topic not found.</error>');
        }

        $filepath = $input->getArgument('file');
        if (!file_exists($filepath)) {
            return $output->writeln('<error>File not found.</error>');
        }

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

        $route = $this->getContainer()->getParameter('sputnik_pubsub.route');
        $callback = $this->getContainer()->get('router')->generate($route, array('id' => $topic->getId()), true);

        $body = file_get_contents($filepath);
        $signature = hash_hmac('sha1', $body, $topic->getTopicSecret());

        $client = $this->getContainer()->get('sputnik_pubsub.http_client');
        $response = $client->post($callback, array('X-Hub-Signature' => 'sha1=' . $signature), $body)->send();
        if ($response->getStatusCode() !== 200) {
            $output->writeln(sprintf('<error>HTTP code returned: %d</error>', $response->getStatusCode()));
        } else {
            $output->writeln(sprintf('<info>HTTP code returned: %d</info>', $response->getStatusCode()));
        }
    }
}
