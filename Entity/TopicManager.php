<?php

namespace Sputnik\Bundle\PubsubBundle\Entity;

use Sputnik\Bundle\PubsubBundle\Model\TopicManagerInterface;
use Sputnik\Common\Manager\DoctrineManager;

/**
 * @package SputnikPubsubBundle_Entity
 */
class TopicManager extends DoctrineManager implements TopicManagerInterface
{
}
