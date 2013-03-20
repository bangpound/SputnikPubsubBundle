<?php

namespace Sputnik\Bundle\PubsubBundle\Tests\Hub;

use Sputnik\Bundle\PubsubBundle\PubsubEvents;
use Sputnik\Bundle\PubsubBundle\Hub\HubProvider;
use Sputnik\Bundle\PubsubBundle\Hub\HubSubscriber;
use Sputnik\Bundle\PubsubBundle\Tests\Fixtures\SampleHub;
use Sputnik\Bundle\PubsubBundle\Tests\Fixtures\Topic;
use Sputnik\Bundle\PubsubBundle\Tests\Fixtures\SampleTopic;

class HubSubscriberTest extends \PHPUnit_Framework_TestCase
{
    private $topicManipulator;
    private $eventDispatcher;
    private $hubProvider;
    private $hubRequest;

    public function setUp()
    {
        $this->topicManipulator = $this->getMockBuilder('Sputnik\\Bundle\\PubsubBundle\\Manipulator\\TopicManipulator')->disableOriginalConstructor()->getMock();
        $this->eventDispatcher = $this->getMockBuilder('Symfony\\Component\\EventDispatcher\\EventDispatcherInterface')->getMock();
        $this->hubRequest = $this->getMockBuilder('Sputnik\\Bundle\\PubsubBundle\\Hub\\HubRequest')->disableOriginalConstructor()->getMock();
        $this->hubProvider = new HubProvider();
        $this->hubProvider->addHub(new SampleHub());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSubscribeOnUnknownHubThrowsException()
    {
        $this->getSubscriber()->subscribe('http://valid-url', 'unknown-hub');
    }

    public function testSubscribeSuccessful()
    {
        $subscriber = $this->getSubscriber();
        $topic = new Topic();

        $this->topicManipulator->expects($this->once())->method('create')->will($this->returnValue($topic));
        $this->eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->equalTo(PubsubEvents::TOPIC_SUBSCRIBE), $this->isInstanceOf('Sputnik\\Bundle\\PubsubBundle\\Event\\TopicAwareEvent'))
        ;
        $this->hubRequest->expects($this->once())->method('sendRequest')->will($this->returnValue(true));

        $this->assertSame($topic, $subscriber->subscribe('http://topic-url', 'sample'));
    }

    public function testSubscribeFailed()
    {
        $subscriber = $this->getSubscriber();
        $topic = new Topic();

        $this->topicManipulator->expects($this->once())->method('create')->will($this->returnValue($topic));
        $this->topicManipulator->expects($this->once())->method('remove');
        $this->hubRequest->expects($this->once())->method('sendRequest')->will($this->returnValue(false));

        $this->assertFalse($subscriber->subscribe('http://topic-url', 'sample'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testUnsubscribeThrowsExceptionOnUnknownHub()
    {
        $this->getSubscriber()->unsubscribe('http://topic-url', 'any-hub');
    }

    public function testUnsubscribeSuccessful()
    {
        $subscriber = $this->getSubscriber();
        $topic = new SampleTopic();

        $this->topicManipulator->expects($this->once())->method('remove')->will($this->returnValue($topic));
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

        $this->topicManipulator->expects($this->once())->method('remove')->will($this->returnValue($topic));
        $this->hubRequest->expects($this->once())->method('sendRequest')->will($this->returnValue(false));

        $this->assertFalse($subscriber->unsubscribe('http://topic-url', 'sample'));
    }

    /**
     * @return HubSubscriber
     */
    private function getSubscriber()
    {
        return new HubSubscriber($this->topicManipulator, $this->hubProvider, $this->hubRequest, $this->eventDispatcher);
    }
}
