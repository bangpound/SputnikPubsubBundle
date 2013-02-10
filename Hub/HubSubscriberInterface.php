<?php

namespace Sputnik\PubsubBundle\Hub;

/**
 * @package SputnikPubsubBundle_Hub
 */
interface HubSubscriberInterface
{
    const SUBSCRIBE   = 'subscribe';
    const UNSUBSCRIBE = 'unsubscribe';

    /**
     * @param string $topicUrl
     * @param string $hubName
     *
     * @return TopicInterface|boolean
     *
     * @throws \InvalidArgumentException
     */
    public function subscribe($topicUrl, $hubName);

    /**
     * @param string $topicUrl
     * @param string $hubName
     *
     * @return TopicInterface|boolean
     *
     * @throws \InvalidArgumentException
     */
    public function unsubscribe($topicUrl, $hubName);
}
