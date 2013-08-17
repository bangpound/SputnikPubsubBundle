<?php

namespace Sputnik\Bundle\PubsubBundle\Doctrine;

use Doctrine\Common\Persistence\ObjectManager;
use PhpOption\Option;
use Sputnik\Bundle\PubsubBundle\Model\TopicInterface;
use Sputnik\Bundle\PubsubBundle\Model\TopicManager as BaseTopicManager;

class TopicManager extends BaseTopicManager
{
    protected $class;
    protected $repository;
    protected $objectManager;

    public function __construct(ObjectManager $om, $class)
    {
        $this->objectManager = $om;
        $this->repository = $om->getRepository($class);

        // Name can be in form BundleName:Class.
        $metadata = $om->getClassMetadata($class);
        $this->class = $metadata->getName();
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param string $id
     *
     * @return \PhpOption\Option
     */
    public function findTopicById($id)
    {
        $result = $this->repository->find($id);

        return Option::fromValue($result);
    }

    /**
     * @param mixed $criteria
     *
     * @return \PhpOption\Option
     */
    public function findTopicBy($criteria)
    {
        $result = $this->repository->findOneBy($criteria);

        return Option::fromValue($result);
    }

    /**
     * @param TopicInterface $topic
     */
    public function updateTopic(TopicInterface $topic)
    {
        $this->objectManager->persist($topic);

        if (func_num_args() == 1) {
            $this->objectManager->flush();
        }
    }

    /**
     * @param TopicInterface $topic
     */
    public function removeTopic(TopicInterface $topic)
    {
        $this->objectManager->remove($topic);

        if (func_num_args() == 1) {
            $this->objectManager->flush();
        }
    }
}
