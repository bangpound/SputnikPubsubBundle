<?php

namespace Sputnik\Bundle\PubsubBundle\Model;

interface TopicManagerInterface
{
    /**
     * @return TopicInterface
     */
    public function create();

    /**
     * @param string $id
     *
     * @return TopicInterface|null
     */
    public function find($id);

    /**
     * @param array $criteria
     *
     * @return TopicInterface|null
     */
    public function findOneBy(array $criteria);

    /**
     * @param TopicInterface $topic
     *
     * @return boolean
     */
    public function persist($topic);

    /**
     * @param TopicInterface $topic
     *
     * @return boolean
     */
    public function remove($topic);

    /**
     * Flush changes.
     */
    public function flush();
}
