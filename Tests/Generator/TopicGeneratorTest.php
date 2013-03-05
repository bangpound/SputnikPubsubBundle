<?php

namespace Sputnik\Bundle\PubsubBundle\Tests\Generator;

use Sputnik\Bundle\PubsubBundle\Tests\Fixtures\SampleTopic;
use Sputnik\Bundle\PubsubBundle\Generator\TopicGenerator;

class TopicGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testGenerateTopicId()
    {
        $generator = new TopicGenerator();
        $topic = new SampleTopic();

        $id = $generator->generateTopicId($topic);
        $this->assertInternalType('string', $id);
        $this->assertEquals(40, strlen($id));

        $id2 = $generator->generateTopicId($topic);
        $this->assertSame($id, $id2, '->generateTopicId() returns same ID');
    }

    public function testGenerateTopicSecret()
    {
        $generator = new TopicGenerator();
        $topic = new SampleTopic();

        $secret = $generator->generateTopicSecret($topic);
        $this->assertInternalType('string', $secret);
        $this->assertStringMatchesFormat('%s.%s', $secret);

        $secret2 = $generator->generateTopicSecret($topic);
        $this->assertNotEquals($secret2, $secret, '->generateTopicSecret() returns new secret string on each invokation');
    }
}
