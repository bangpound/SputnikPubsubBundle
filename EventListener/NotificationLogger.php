<?php

namespace Sputnik\Bundle\PubsubBundle\EventListener;

use Psr\Log\LoggerInterface;
use Sputnik\Bundle\PubsubBundle\Event\NotificationReceivedEvent;

class NotificationLogger
{
    private $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param NotificationReceivedEvent $event
     */
    public function onNotificationReceived(NotificationReceivedEvent $event)
    {
        $headers = '';
        foreach ($event->getHeaders() as $name => $value) {
            $headers .= "\n$name => {$value[0]}";
        }

        $this->logger->debug(sprintf("Pubsub notification:\nTopic: %s [%s]\nHeaders: %s\nContent:\n%s",
            (string) $event->getTopic(),
            $event->getTopic()->getId(),
            $headers,
            $event->getContent()
        ));
    }
}
