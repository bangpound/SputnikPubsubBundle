<?php

namespace Sputnik\Bundle\PubsubBundle\Generator;

use Sputnik\Bundle\PubsubBundle\Model\TopicInterface;

class TopicGenerator implements TopicGeneratorInterface
{
    /**
     * {@inheritdoc}
     */
    public function generateTopicId(TopicInterface $topic)
    {
        return sha1($topic->getTopicUrl() . $topic->getHubName());
    }

    /**
     * {@inheritdoc}
     */
    public function generateTopicSecret(TopicInterface $topic)
    {
        return uniqid(rand(), true) . time();
    }
}
