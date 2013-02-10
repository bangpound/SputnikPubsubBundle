<?php

namespace Sputnik\PubsubBundle\Entity;

use Sputnik\PubsubBundle\Model\TopicManagerInterface;
use Sputnik\Common\Manager\DoctrineManager;

/**
 * @package SputnikPubsubBundle_Entity
 */
class TopicManager extends DoctrineManager implements TopicManagerInterface
{
}
