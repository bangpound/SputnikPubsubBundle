<?php

namespace Sputnik\Bundle\PubsubBundle\Tests\Fixtures;

use Sputnik\Bundle\PubsubBundle\Model\Topic;

/**
 * @package SputnikPubsubBundle_Tests
 * @subpackage Fixtures
 */
class SampleTopic extends Topic
{
    protected $topicUrl = 'http://www.provider.com/sample-topic-url';
    protected $hubName = 'sample';
    protected $topicSecret = 'secret';
}
