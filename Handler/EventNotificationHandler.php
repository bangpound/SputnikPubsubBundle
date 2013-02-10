<?php

namespace Sputnik\PubsubBundle\Handler;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\HeaderBag;
use Sputnik\PubsubBundle\Event\NotificationReceivedEvent;
use Sputnik\PubsubBundle\Model\TopicInterface;
use Sputnik\PubsubBundle\PubsubEvents;

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
