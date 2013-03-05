<?php

namespace Sputnik\Bundle\PubsubBundle\Entity;

use Sputnik\Bundle\PubsubBundle\Model\TopicManagerInterface;
use Sputnik\Common\Manager\DoctrineManager;

class TopicManager extends DoctrineManager implements TopicManagerInterface
{
}
