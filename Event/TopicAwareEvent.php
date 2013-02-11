<?php

namespace Sputnik\Bundle\PubsubBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Sputnik\Bundle\PubsubBundle\Model\TopicInterface;

/**
 * @package SputnikPubsubBundle_Event
 */
class TopicAwareEvent extends Event
{
    private $topic;

    public function __construct(TopicInterface $topic)
    {
        $this->topic = $topic;
    }

    /**
     * @return TopicInterface
     */
    public function getTopic()
    {
        return $this->topic;
    }
}
