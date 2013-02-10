<?php

namespace Sputnik\PubsubBundle\Event;

use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\EventDispatcher\Event;
use Sputnik\PubsubBundle\Model\TopicInterface;
use Sputnik\PubsubBundle\Event\TopicAwareEvent;

/**
 * @package SputnikPubsubBundle_Event
 */
class NotificationReceivedEvent extends TopicAwareEvent
{
    private $headers;
    private $content;

    /**
     * @param TopicInterface $topic
     * @param HeaderBag      $headers
     * @param string         $content
     */
    public function __construct(TopicInterface $topic, HeaderBag $headers, $content)
    {
        parent::__construct($topic);

        $this->headers = $headers;
        $this->content = $content;
    }

    /**
     * @return HeaderBag
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
}
