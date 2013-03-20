<?php

namespace Sputnik\Bundle\PubsubBundle\Mandango;

use Sputnik\Bundle\PubsubBundle\Model\TopicInterface;

abstract class Topic extends \Model\SputnikPubsubBundle\Base\Topic implements TopicInterface
{
    public function initializeDefaults()
    {
        $this->setCreatedAt(new \DateTime);
        $this->setUpdatedAt(new \DateTime);
    }

    /**
     * {@inheritdoc}
     */
    public function incrementUpdatedAt()
    {
        $this->setUpdatedAt(new \DateTime);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->getHubName() . ':' . $this->getTopicUrl();
    }
}
