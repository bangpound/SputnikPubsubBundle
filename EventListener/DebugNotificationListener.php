<?php

namespace Sputnik\PubsubBundle\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Sputnik\PubsubBundle\PubsubEvents;
use Sputnik\PubsubBundle\Event\NotificationReceivedEvent;

/**
 * @package SputnikPubsubBundle_EventListener
 */
class DebugNotificationListener implements EventSubscriberInterface
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
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(PubsubEvents::NOTIFICATION_RECEIVED => array('onNotificationReceived', 100));
    }

    /**
     * @param NotificationRecievedEvent $event
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
