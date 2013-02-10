<?php

namespace Sputnik\PubsubBundle\Model;

/**
 * @package SputnikPubsubBundle_Model
 */
abstract class Topic implements TopicInterface
{
    protected $id;
    protected $topicUrl;
    protected $topicSecret;
    protected $verified;
    protected $createdAt;
    protected $updatedAt;
    protected $hubName;

    public function __construct()
    {
        $this->createdAt = $this->updatedAt = new \DateTime;
        $this->verified = false;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTopicUrl()
    {
        return $this->topicUrl;
    }

    /**
     * {@inheritdoc}
     */
    public function setTopicUrl($url)
    {
        $this->topicUrl = $url;
    }

    /**
     * @return string
     */
    public function getTopicSecret()
    {
        return $this->topicSecret;
    }

    /**
     * {@inheritdoc}
     */
    public function setTopicSecret($secret)
    {
        $this->topicSecret = $secret;
    }

    /**
     * {@inheritdoc}
     */
    public function isVerified()
    {
        return $this->verified;
    }

    /**
     * {@inheritdoc}
     */
    public function setVerified($flag)
    {
        $this->verified = $flag;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * {@inheritdoc}
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * {@inheritdoc}
     */
    public function incrementUpdatedAt()
    {
        $this->updatedAt = new \DateTime;
    }

    /**
     * {@inheritdoc}
     */
    public function getHubName()
    {
        return $this->hubName;
    }

    /**
     * {@inheritdoc}
     */
    public function setHubName($name)
    {
        $this->hubName = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->hubName . ':' . $this->topicUrl;
    }
}
