<?php

namespace Sputnik\PubsubBundle\Handler;

use Symfony\Component\HttpFoundation\HeaderBag;
use Sputnik\PubsubBundle\Model\TopicInterface;

/**
 * @package SputnikPubsubBundle_Handler
 */
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
