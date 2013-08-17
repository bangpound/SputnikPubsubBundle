<?php

namespace Sputnik\Bundle\PubsubBundle\Manipulator;

use Sputnik\Bundle\PubsubBundle\Model\TopicInterface;
use Sputnik\Bundle\PubsubBundle\Generator\TopicGeneratorInterface;
use Sputnik\Bundle\PubsubBundle\Model\TopicManagerInterface;

class TopicManipulator
{
    private $manager;
    private $generator;

    public function __construct(TopicManagerInterface $manager, TopicGeneratorInterface $generator)
    {
        $this->manager = $manager;
        $this->generator = $generator;
    }

    /**
     * @param string $topicUrl
     * @param string $hubName
     *
     * @return TopicInterface
     *
     * @throws \InvalidArgumentException
     */
    public function create($topicUrl, $hubName)
    {
        if (!filter_var($topicUrl, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException(sprintf('Invalid URL: %s', $topicUrl));
        }

        $topic = $this->manager->createTopic();
        $topic->setHubName($hubName);
        $topic->setTopicUrl($topicUrl);

        $id = $this->generator->generateTopicId($topic);

        $topic = $this->manager->findTopicById($id)->getOrCall(function () use ($topic) { return $topic; });

        $topic->setId($id);
        $topic->setTopicSecret($this->generator->generateTopicSecret($topic));

        $this->manager->updateTopic($topic);

        return $topic;
    }

    /**
     * @param string $topicUrl
     * @param string $hubName
     *
     * @throws \InvalidArgumentException
     *
     * @return TopicInterface
     */
    public function remove($topicUrl, $hubName)
    {
        $criteria = array('topicUrl' => $topicUrl, 'hubName' => $hubName);

        $topic = $this->manager->findTopicBy($criteria)->getOrCall(function () use ($hubName, $topicUrl) {
            throw new \InvalidArgumentException(sprintf('Topic not found - %s:%s', $hubName, $topicUrl));
        });

        $this->manager->removeTopic($topic);

        return $topic;
    }
}
