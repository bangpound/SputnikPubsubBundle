<?php

namespace Sputnik\Bundle\PubsubBundle\Tests\Hub;

use Sputnik\Bundle\PubsubBundle\PubsubEvents;
use Sputnik\Bundle\PubsubBundle\Hub\HubProvider;
use Sputnik\Bundle\PubsubBundle\Hub\HubSubscriber;
use Sputnik\Bundle\PubsubBundle\Tests\Fixtures\SampleHub;
use Sputnik\Bundle\PubsubBundle\Tests\Fixtures\Topic;
use Sputnik\Bundle\PubsubBundle\Tests\Fixtures\SampleTopic;

/**
 * @package SputnikPubsubBundle_Tests
 * @subpackage Hub
 */
class HubSubscriberTest extends \PHPUnit_Framework_TestCase
{
    private $eventDispatcher;
    private $topicManager;
    private $topicGenerator;
    private $hubProvider;
    private $hubRequest;

    public function setUp()
    {
        $this->eventDispatcher = $this->getMockBuilder('Symfony\\Component\\EventDispatcher\\EventDispatcherInterface')->getMock();
        $this->topicManager = $this->getMockBuilder('Sputnik\\Bundle\\PubsubBundle\\Model\\TopicManagerInterface')->getMock();
        $this->topicGenerator = $this->getMockBuilder('Sputnik\\Bundle\\PubsubBundle\\Generator\\TopicGeneratorInterface')->getMock();
        $this->hubRequest = $this->getMockBuilder('Sputnik\\Bundle\\PubsubBundle\\Hub\\HubRequest')->disableOriginalConstructor()->getMock();
        $this->hubProvider = new HubProvider();
        $this->hubProvider->addHub(new SampleHub());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSubscribeOnInvalidTopicUrlThrowsException()
    {
        $this->getSubscriber()->subscribe('invalid-url', 'hub-name');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSubscribeOnUnknownHubThrowsException()
    {
        $this->getSubscriber()->subscribe('http://valid-url', 'unknown-hub');
    }

    public function testSubscribeNewTopic()
    {
        $subscriber = $this->getSubscriber();
        $topic = new Topic();

        $this->topicManager->expects($this->once())->method('create')->will($this->returnValue($topic));
        $this->topicManager->expects($this->once())->method('find');
        $this->topicManager->expects($this->once())->method('persist')->with($this->identicalTo($topic));
        $this->topicManager->expects($this->once())->method('flush');

        $this->topicGenerator->expects($this->once())->method('generateTopicId')->with($this->identicalTo($topic))->will($this->returnValue('id'));
        $this->topicGenerator->expects($this->once())->method('generateTopicSecret')->with($this->identicalTo($topic))->will($this->returnValue('secret'));

        $this->eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->equalTo(PubsubEvents::TOPIC_SUBSCRIBE), $this->isInstanceOf('Sputnik\\Bundle\\PubsubBundle\\Event\\TopicAwareEvent'))
        ;

        $this->hubRequest->expects($this->once())->method('sendRequest')->will($this->returnValue(true));

        $this->assertSame($topic, $subscriber->subscribe('http://topic-url', 'sample'));

        $this->assertEquals('sample', $topic->getHubName());
        $this->assertEquals('http://topic-url', $topic->getTopicUrl());
        $this->assertEquals('id', $topic->getId());
        $this->assertEquals('secret', $topic->getTopicSecret());
    }

    public function testSubscribeFailed()
    {
        $subscriber = $this->getSubscriber();
        $topic = new Topic();

        $this->topicManager->expects($this->once())->method('create')->will($this->returnValue($topic));
        $this->topicManager->expects($this->once())->method('find');
        $this->topicManager->expects($this->once())->method('persist')->with($this->identicalTo($topic));
        $this->topicManager->expects($this->once())->method('remove')->with($this->identicalTo($topic));
        $this->topicManager->expects($this->exactly(2))->method('flush');

        $this->hubRequest->expects($this->once())->method('sendRequest')->will($this->returnValue(false));

        $this->assertFalse($subscriber->subscribe('http://topic-url', 'sample'));
    }

    public function testSubscribeToExistingTopic()
    {
        $subscriber = $this->getSubscriber();
        $newTopic = new Topic();
        $existingTopic = new SampleTopic();

        $this->topicManager->expects($this->once())->method('create')->will($this->returnValue($newTopic));
        $this->topicManager->expects($this->once())->method('find')->will($this->returnValue($existingTopic));
        $this->topicManager->expects($this->once())->method('persist')->with($this->identicalTo($existingTopic));
        $this->topicManager->expects($this->once())->method('flush');

        $this->topicGenerator->expects($this->once())->method('generateTopicId')->with($this->identicalTo($newTopic))->will($this->returnValue('id'));
        $this->topicGenerator->expects($this->once())->method('generateTopicSecret')->with($this->identicalTo($existingTopic))->will($this->returnValue('secret'));

        $this->eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->equalTo(PubsubEvents::TOPIC_SUBSCRIBE), $this->isInstanceOf('Sputnik\\Bundle\\PubsubBundle\\Event\\TopicAwareEvent'))
        ;

        $this->hubRequest->expects($this->once())->method('sendRequest')->will($this->returnValue(true));

        $this->assertSame($existingTopic, $subscriber->subscribe('http://www.provider.com/sample-topic-url', 'sample'));
        $this->assertEquals('secret', $existingTopic->getTopicSecret()); // Topic secret was regenerated for existing topic
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testUnsubscribeThrowsExceptionOnMissingTopic()
    {
        $subscriber = $this->getSubscriber();

        $this->topicManager
            ->expects($this->once())
            ->method('findOneBy')
            ->with($this->equalTo(array('topicUrl' => 'http://topic-url', 'hubName' => 'hub')))
        ;

        $subscriber->unsubscribe('http://topic-url', 'hub');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testUnsubscribeThrowsExceptionOnUnknownHub()
    {
        $subscriber = $this->getSubscriber();
        $topic = new Topic();

        $this->topicManager->expects($this->once())->method('findOneBy')->will($this->returnValue($topic));

        $subscriber->unsubscribe('http://topic-url', 'any-hub');
    }

    public function testUnsubscribeSuccessful()
    {
        $subscriber = $this->getSubscriber();
        $topic = new SampleTopic();

        $this->topicManager->expects($this->once())->method('findOneBy')->will($this->returnValue($topic));
        $this->topicManager->expects($this->once())->method('remove')->with($this->identicalTo($topic));
        $this->topicManager->expects($this->once())->method('flush');

        $this->eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->equalTo(PubsubEvents::TOPIC_UNSUBSCRIBE), $this->isInstanceOf('Sputnik\\Bundle\\PubsubBundle\\Event\\TopicAwareEvent'))
        ;

        $this->hubRequest->expects($this->once())->method('sendRequest')->will($this->returnValue(true));

        $this->assertSame($topic, $subscriber->unsubscribe('http://topic-url', 'sample'));
    }

    public function testUnsubscribeFailed()
    {
        $subscriber = $this->getSubscriber();
        $topic = new SampleTopic();

        $this->topicManager->expects($this->once())->method('findOneBy')->will($this->returnValue($topic));

        $this->hubRequest->expects($this->once())->method('sendRequest')->will($this->returnValue(false));

        $this->assertFalse($subscriber->unsubscribe('http://topic-url', 'sample'));
    }

    /**
     * @return HubSubscriber
     */
    private function getSubscriber()
    {
        return new HubSubscriber($this->topicManager, $this->topicGenerator, $this->hubProvider, $this->hubRequest, $this->eventDispatcher);
    }
}
