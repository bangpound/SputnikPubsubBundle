<?php

namespace Sputnik\Bundle\PubsubBundle\Model;

interface TopicManagerInterface
{
    /**
     * @return string
     */
    public function getClass();

    /**
     * @return TopicInterface
     */
    public function createTopic();

    /**
     * @param string $id
     *
     * @return \PhpOption\Option
     */
    public function findTopicById($id);

    /**
     * @param mixed $criteria
     *
     * @return \PhpOption\Option
     */
    public function findTopicBy($criteria);

    /**
     * @param TopicInterface $topic
     */
    public function updateTopic(TopicInterface $topic);

    /**
     * @param TopicInterface $topic
     */
    public function removeTopic(TopicInterface $topic);
}
