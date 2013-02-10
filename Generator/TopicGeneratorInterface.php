<?php

namespace Sputnik\PubsubBundle\Generator;

use Sputnik\PubsubBundle\Model\TopicInterface;

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
