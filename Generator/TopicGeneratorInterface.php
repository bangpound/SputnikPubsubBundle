<?php

namespace Sputnik\Bundle\PubsubBundle\Generator;

use Sputnik\Bundle\PubsubBundle\Model\TopicInterface;

/**
 * @package SputnikPubsubBundle_Generator
 */
interface TopicGeneratorInterface
{
    /**
     * @param TopicInterface $topic
     *
     * @return string
     */
    public function generateTopicId(TopicInterface $topic);

    /**
     * @param TopicInterface $topic
     *
     * @return string
     */
    public function generateTopicSecret(TopicInterface $topic);
}
