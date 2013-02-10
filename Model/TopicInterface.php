<?php

namespace Sputnik\PubsubBundle\Model;

/**
 * @package SputnikPubsubBundle_Model
 */
interface TopicInterface
{
    /**
     * @return string
     */
    public function getId();

    /**
     * @param string $id
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getTopicUrl();

    /**
     * @param string $url
     */
    public function setTopicUrl($url);

    /**
     * @return string
     */
    public function getTopicSecret();

    /**
     * @param string $secret
     */
    public function setTopicSecret($secret);

    /**
     * @return boolean
     */
    public function isVerified();

    /**
     * @param boolean $flag
    */
    public function setVerified($flag);

    /**
     * @return \DateTime
     */
    public function getCreatedAt();

    /**
     * @return \DateTime
     */
    public function getUpdatedAt();

    /**
     * @return void
     */
    public function incrementUpdatedAt();

    /**
     * @return string
     */
    public function getHubName();

    /**
     * @param string $name
     */
    public function setHubName($name);

    /**
     * @return string
     */
    public function __toString();
}
