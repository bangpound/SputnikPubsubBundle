<?php

namespace Sputnik\PubsubBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sputnik\PubsubBundle\Model\Topic as BaseTopic;

/**
 * @package SputnikPubsubBundle_Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="pubsub_topic")
 * @ORM\ChangeTrackingPolicy("DEFERRED_EXPLICIT")
 * @ORM\HasLifecycleCallbacks
 */
class Topic extends BaseTopic
{
    /**
     * @ORM\Id
     * @ORM\Column
     */
    protected $id;

    /**
     * @ORM\Column(name="topic_url")
     */
    protected $topicUrl;

    /**
     * @ORM\Column(name="topic_secret")
     */
    protected $topicSecret;

    /**
     * @ORM\Column(name="is_verified", type="boolean")
     */
    protected $verified;

    /**
     * @ORM\Column(type="datetime", name="date_created")
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="datetime", name="date_updated")
     */
    protected $updatedAt;

    /**
     * @ORM\Column(name="hub_name")
     */
    protected $hubName;

    /**
     * @ORM\PreUpdate
     */
    public function incrementUpdatedAt()
    {
        $this->updatedAt = new \DateTime;
    }
}
