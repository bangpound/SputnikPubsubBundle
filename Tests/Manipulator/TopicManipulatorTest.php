<?php

namespace Sputnik\Bundle\PubsubBundle\Tests\Manipulator;

use Sputnik\Bundle\PubsubBundle\Tests\Fixtures\SampleTopic;
use Sputnik\Bundle\PubsubBundle\Tests\Fixtures\Topic;
use Sputnik\Bundle\PubsubBundle\Manipulator\TopicManipulator;

class TopicManipulatorTest extends \PHPUnit_Framework_TestCase
{
    private $topicManager;
    private $topicGenerator;

    public function setUp()
    {
        $this->topicManager = $this->getMockBuilder('Sputnik\\Bundle\\PubsubBundle\\Model\\TopicManagerInterface')->getMock();
        $this->topicGenerator = $this->getMockBuilder('Sputnik\\Bundle\\PubsubBundle\\Generator\\TopicGeneratorInterface')->getMock();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCreateOnInvalidTopicUrlThrowsException()
    {
        $this->getManipulator()->create('invalid-url', 'hub-name');
    }

    public function testCreateNewTopic()
    {
        $manipulator = $this->getManipulator();
        $topic = new Topic();

        $this->topicManager->expects($this->once())->method('create')->will($this->returnValue($topic));
        $this->topicManager->expects($this->once())->method('find');
        $this->topicManager->expects($this->once())->method('persist')->with($this->identicalTo($topic));
        $this->topicManager->expects($this->once())->method('flush');

        $this->topicGenerator->expects($this->once())->method('generateTopicId')->with($this->identicalTo($topic))->will($this->returnValue('id'));
        $this->topicGenerator->expects($this->once())->method('generateTopicSecret')->with($this->identicalTo($topic))->will($this->returnValue('secret'));

        $this->assertSame($topic, $manipulator->create('http://topic-url', 'sample'));

        $this->assertEquals('sample', $topic->getHubName());
        $this->assertEquals('http://topic-url', $topic->getTopicUrl());
        $this->assertEquals('id', $topic->getId());
        $this->assertEquals('secret', $topic->getTopicSecret());
    }

    public function testCreateExistingTopic()
    {
        $manipulator = $this->getManipulator();
        $newTopic = new Topic();
        $existingTopic = new SampleTopic();

        $this->topicManager->expects($this->once())->method('create')->will($this->returnValue($newTopic));
        $this->topicManager->expects($this->once())->method('find')->will($this->returnValue($existingTopic));
        $this->topicManager->expects($this->once())->method('persist')->with($this->identicalTo($existingTopic));
        $this->topicManager->expects($this->once())->method('flush');

        $this->topicGenerator->expects($this->once())->method('generateTopicId')->with($this->identicalTo($newTopic))->will($this->returnValue('id'));
        $this->topicGenerator->expects($this->once())->method('generateTopicSecret')->with($this->identicalTo($existingTopic))->will($this->returnValue('secret'));

        $this->assertSame($existingTopic, $manipulator->create('http://www.provider.com/sample-topic-url', 'sample'));
        $this->assertEquals('secret', $existingTopic->getTopicSecret()); // Topic secret was regenerated for existing topic
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRemoveThrowsExceptionOnMissingTopic()
    {
        $manipulator = $this->getManipulator();

        $this->topicManager
            ->expects($this->once())
            ->method('findOneBy')
            ->with($this->equalTo(array('topicUrl' => 'http://topic-url', 'hubName' => 'hub')))
        ;

        $manipulator->remove('http://topic-url', 'hub');
    }

    public function testRemove()
    {
        $manipulator = $this->getManipulator();
        $topic = new Topic();

        $this->topicManager->expects($this->once())->method('findOneBy')->will($this->returnValue($topic));
        $this->topicManager->expects($this->once())->method('remove')->with($this->identicalTo($topic));
        $this->topicManager->expects($this->once())->method('flush');

        $this->assertSame($topic, $manipulator->remove('http://topic-url', 'sample'));
    }

    /**
     * @return TopicManipulator
     */
    private function getManipulator()
    {
        return new TopicManipulator($this->topicManager, $this->topicGenerator);
    }
}
