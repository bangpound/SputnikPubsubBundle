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
            throw new \InvalidArgumentException(sprintf('invalid URL: %s', $topicUrl));
        }

        $topic = $this->manager->create();
        $topic->setHubName($hubName);
        $topic->setTopicUrl($topicUrl);

        $id = $this->generator->generateTopicId($topic);

        $current = $this->manager->find($id);
        if ($current instanceof TopicInterface) {
            $topic = $current;
        }

        $topic->setId($id);
        $topic->setTopicSecret($this->generator->generateTopicSecret($topic));

        $this->manager->persist($topic);
        $this->manager->flush();

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
        $topic = $this->manager->findOneBy(array('topicUrl' => $topicUrl, 'hubName' => $hubName));
        if (!$topic instanceof TopicInterface) {
            throw new \InvalidArgumentException(sprintf('topic not found - %s:%s', $hubName, $topicUrl));
        }

        $this->manager->remove($topic);
        $this->manager->flush();

        return $topic;
    }
}
