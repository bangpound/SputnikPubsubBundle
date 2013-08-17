<?php

namespace Sputnik\Bundle\PubsubBundle\Hub;

interface HubSubscriberInterface
{
    const SUBSCRIBE   = 'subscribe';
    const UNSUBSCRIBE = 'unsubscribe';

    /**
     * @param string $topicUrl
     * @param string $hubName
     *
     * @return \Sputnik\Bundle\PubsubBundle\Model\TopicInterface|boolean
     *
     * @throws \InvalidArgumentException
     */
    public function subscribe($topicUrl, $hubName);

    /**
     * @param string $topicUrl
     * @param string $hubName
     *
     * @return \Sputnik\Bundle\PubsubBundle\Model\TopicInterface|boolean
     *
     * @throws \InvalidArgumentException
     */
    public function unsubscribe($topicUrl, $hubName);
}
