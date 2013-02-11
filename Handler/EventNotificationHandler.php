<?php

namespace Sputnik\Bundle\PubsubBundle\Handler;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\HeaderBag;
use Sputnik\Bundle\PubsubBundle\Event\NotificationReceivedEvent;
use Sputnik\Bundle\PubsubBundle\Model\TopicInterface;
use Sputnik\Bundle\PubsubBundle\PubsubEvents;

/**
 * @package SputnikPubsubBundle_Handler
 */
class EventNotificationHandler implements NotificationHandlerInterface
{
    private $dispatcher;

    /**
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(TopicInterface $topic, HeaderBag $headers, $content)
    {
        $this->dispatcher->dispatch(PubsubEvents::NOTIFICATION_RECEIVED, new NotificationReceivedEvent($topic, $headers, $content));
    }
}
