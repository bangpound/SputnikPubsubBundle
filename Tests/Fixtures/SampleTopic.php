<?php

namespace Sputnik\Bundle\PubsubBundle\Tests\Fixtures;

class SampleTopic extends Topic
{
    protected $topicUrl = 'http://www.provider.com/sample-topic-url';
    protected $hubName = 'sample';
    protected $topicSecret = 'secret';
}
