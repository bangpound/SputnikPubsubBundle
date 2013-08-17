<?php

namespace Sputnik\Bundle\PubsubBundle\Model;

abstract class TopicManager implements TopicManagerInterface
{
    public function createTopic()
    {
        $class = $this->getClass();

        return new $class;
    }
}
