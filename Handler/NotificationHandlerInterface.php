<?php

namespace Sputnik\Bundle\PubsubBundle\Handler;

use Symfony\Component\HttpFoundation\HeaderBag;
use Sputnik\Bundle\PubsubBundle\Model\TopicInterface;

interface NotificationHandlerInterface
{
    /**
     * @param TopicInterface $topic
     * @param HeaderBag      $headers
     * @param string         $content
     *
     * @return mixed
     */
    public function handle(TopicInterface $topic, HeaderBag $headers, $content);
}
